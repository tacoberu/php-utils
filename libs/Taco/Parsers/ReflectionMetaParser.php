<?php
/**
 * This file is part of the Taco Projects.
 *
 * Copyright (c) 2004, 2013 Martin Takáč (http://martin.takac.name)
 *
 * For the full copyright and license information, please view
 * the file LICENCE that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @author     Martin Takáč (martin@takac.name)
 */

namespace Taco\Parsers;


use Nette\Utils,
	Nette\Reflection;
use RuntimeException,
	InvalidArgumentException;


/**
 * Získání definice prvků reflexí nějakého doménového objektu.
 */
class ReflectionMetaParser
{


	private $excludeMethods = array('getReflection');



	/**
	 * @param string $name
	 */
	function addExcludeMethod($name)
	{
		Utils\Validators::assert($name, 'string:1..');
		$this->excludeMethods[] = $as;
		return $this;
	}



	/**
	 * Proleze třídu, a vytáhne z toho informace o jednoltivých prvcích formuláře.
	 *
	 * @param string | object Pro který se vytvářejí controls.
	 *
	 * @return array of stdClass
	 */
	function parse($entry)
	{
		if (is_string($entry)) {
			$reflection = new Reflection\ClassType($entry);
			return $this->parseControls($reflection);
		}
		elseif (is_object($entry)) {
			$reflection = new Reflection\ClassType($entry);
			return $this->parseControls($reflection);
		}
		else {
			throw new InvalidArgumentException("Unsupported type of entry.");
		}
	}



	// -- PRIVATE ------------------------------------------------------



	/**
	 * Proleze třídu, a vytáhne z toho informace o jednoltivých prvcích
	 * třídy. Prvky jsou poněkud netriviálně seřazeny podle toho, jak jsou
	 * umístěny jejich definice ve třídě. Přičemž je přihlédnuto hlavně k
	 * umístění properites. Neníli se čeho chytit, zařadí se podle abecedy.
	 *
	 * @return array of stdClass
	 */
	private function parseControls($reflection)
	{
		// Vytvoříme tabulku umístění
		$controls = array_flip(array_map(function($m) {
			return $m->getName();
		}, $reflection->getProperties()));

		$prev = Null;

		// Getters
		foreach (get_class_methods($reflection->name) as $name) {
			if (! in_array($name, $this->excludeMethods) && Utils\Strings::startsWith($name, 'get')) {
				$k = lcfirst(substr($name, 3));

				$getter = $reflection->getMethod($name);
				$required = $getter->getAnnotation('required');

				// Pokud nemá setter, tak nevytváříme
				if (! $reflection->hasMethod('set' . $k) || ! $reflection->getMethod('set' . $k)->isPublic()) {
					if (! $required && ! $getter->getAnnotation('editable')) {
						continue;
					}
				}
				else {
					$setter = $reflection->getMethod('set' . $k);
					if (! $required) {
						$required = $setter->getAnnotation('required');
					}
				}

				$meta = $getter->getAnnotation('meta');
				$label = isset($meta['label']) ? $meta['label'] : ucfirst($k);
				$type = isset($meta['type']) ? $meta['type'] : $reflection->getMethod($name)->getAnnotation('return');

				// Virtuální getter.
				if (! array_key_exists($k, $controls)) {
					if (empty($prev)) {
						$prev = self::lookupPrevKeyFor(array_keys($controls), $k);
					}
					if (empty($prev)) {
						Utils\Arrays::insertBefore($controls, $prev, array($k => 1));
					}
					else {
						Utils\Arrays::insertAfter($controls, $prev, array($k => 1));
					}
				}

				$controls[$k] = self::buildControl($k, $label, $type, $required);

				// Respektovat řadu getterů.
				$prev = $k;
			}
		}
		// Public fields
		foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
			/*
			if ($prop->isStatic()) {
				continue;
			}

			if ($prop->isPublic()) {
				$return = $prop->getAnnotation('var');
				$name = $prop->name;
				$label = $prop->name;
			}
			else {
				dump($prop->hasMethod('get' . ));
			} */

			$name = $prop->name;
			$meta = $prop->getAnnotation('meta');
			$label = isset($meta['label']) ? $meta['label'] : ucfirst($prop->name);
			$type = isset($meta['type']) ? $meta['type'] : $prop->getAnnotation('var');
			$required = $prop->getAnnotation('required');

			$controls[$name] = self::buildControl($name, $label, $type, $required);
		}

		// Odstranit fantomy.
		$controls = array_filter($controls, function($m) {
			return is_object($m);
		});

		return $controls;
	}



	/**
	 * Helper pro vytvoření balíčku informací o controlu.
	 *
	 * @param string $name Jméno atributu
	 * @param string $label Lidský popisek atributu.
	 * @param string $type Typ hodnoty, číslo, text, boolean.
	 * @param bool $required Zda je nutné hodnotu vyplnit - šikovné pro formuláře.
	 *
	 * @return stdClass
	 */
	private static function buildControl($name, $label, $type = Null, $required = False)
	{
		Utils\Validators::assert($name, 'string:1..');
		Utils\Validators::assert($label, 'string:1..');
		return (object) array(
				'name' => $name,
				'label' => $label,
				'type' => $type ?: 'text',
				'required' => (bool)$required,
				);
	}



	/**
	 * Vyhledá, za který klíč v tabulce se má vložit.
	 *
	 * @param array Tabulka možností.
	 * @param string zařazovaný klíč.
	 * @return string Najde a vrátí klíč, za který se má přiřadit. V krajním případě na konec.
	 */
	private static function lookupPrevKeyFor($table, $key)
	{
		Utils\Validators::assert($table, 'list');
		Utils\Validators::assert($key, 'string:1..');
		$table[] = $key;
		sort($table);
		$indexes = array_flip($table);
		if (! $index = $indexes[$key]) {
			return Null;
		}
		return $table[$index-1];
	}

}
