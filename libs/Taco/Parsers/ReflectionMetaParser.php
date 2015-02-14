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
 * Získání definice formuláře reflexí nějaké entity.
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
	 * Formulář pro nový záznam.
	 *
	 * @param array | object Pro který se vytvářejí controls.
	 *
	 * @return array
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
	 * Proleze třídu, a vytáhne z toho informace o jednoltivých prvcích formuláře.
	 *
	 * @return array of stdClass
	 */
	private function parseControls($reflection)
	{
		$controls = array();

		// Getters
		foreach (get_class_methods($reflection->name) as $name) {
			if (! in_array($name, $this->excludeMethods) && Utils\Strings::startsWith($name, 'get')) {
				$k = strtolower(substr($name, 3));

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

				$controls[$k] = self::buildControl($k, $label, $type, $required);
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

		return $controls;
	}



	/**
	 * Helper pro vytvoření balíčku informací o controlu.
	 *
	 * @return stdClass
	 */
	private static function buildControl($name, $label, $type = Null, $required = False)
	{
		return (object) array(
				'name' => $name,
				'label' => $label,
				'type' => $type ?: 'text',
				'required' => (bool)$required,
				);
	}


}
