<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Parsers;

use RuntimeException;


/**
 * Rozbije to text na tokeny oddělené mezerou. Přičemž zachovává sekvence uvozené uvozovkama a apostrofama.
 * Pokud máme nějaký text ohraničený tagy, tak z něj udělá subtoken.
 * Nemáme zájem nějak složitěji mapovat nebo zpracovávat jednotlivé tokeny. Jde nám čistě a
 * pouze o ohraničení jednotlivých fragmentů. Aby se nehledali tagy v textu.
 *
 * @TODO Tagy jako taky strom.
 * @TODO Ukončení více možnými tagy.
 * @TODO Sebeukončovací tag. Tzn, že tag skončí tím, že začne jiný.
 * @TODO Nezvládne text formátovaný odsazováním aka python.
 */
class Tokenizer
{

	/**
	 * @var array of pair of string
	 */
	private $tags;


	function __construct($tags = array(array('(', ')')))
	{
		$this->tags = $tags;
	}



	function tokenize($src)
	{
		$def = $this->buildTree2($src, 0);
		return $def[0]->getItems();
	}



	/**
	 * Rozbije to text na tokeny oddělené mezerou. Přičemž zachovává sekvence uvozené uvozovkama a apostrofama.
	 *
	 * @TODO Není to úplně přesný. Pro řetězec ~abc def"veta"pok xyz~ to na urovni uvozovek zalomí.
	 */
	static function parse($src)
	{
		$res = array();
		// Najdeme první uvozovku. Kod do ní zpracujeme.
		while ($quote = TextParser::indexOfText($src)) {
			$head = substr($src, 0, $quote[1]);
			$res = array_merge($res, explode(' ', rtrim($head)));
			$src = substr($src, $quote[1]);

			// Najdeme druhou uvozovku.
			$index = TextParser::indexOfQuotes($quote[0], $src, 1);
			if ($index < 0) {
				break;
			}
			$res[] = substr($src, 0, $index + 1);
			$src = trim(substr($src, $index + 1));
		}

		if ($src) {
			$res = array_merge($res, explode(' ', $src));
		}

		return array_values(array_filter($res));
	}



	/**
	 * Split the $src using $sep. Keeps text content (quotes, apostrophes). Escaping (by /) is supported.
	 *
	 * @param string
	 * @param string
	 * @return array of string
	 */
	static function split($sep, $src)
	{
		$res = array();
		$glue = False;
		// Najdeme první uvozovku. Kod do ní zpracujeme.
		while ($quote = TextParser::indexOfText($src)) {
			$head = substr($src, 0, $quote[1]);
			$chunks = explode($sep, $head);
			if ($glue) {
				$first = array_shift($chunks);
				$last = array_pop($res);
				$res[] = $last . $first;
				$glue = False;
			}
			$res = array_merge($res, $chunks);
			$src = substr($src, $quote[1]);

			// Najdeme druhou uvozovku.
			$index = TextParser::indexOfQuotes($quote[0], $src, 1);
			if ($index < 0) {
				break;
			}

			// text v uvozovkách přilípnout k poslednímu prvku
			$last = array_pop($res);
			$last .= substr($src, 0, $index + 1);
			$res[] = $last;
			$glue = True;

			$src = substr($src, $index + 1);
		}

		// zbytek
		if ($src) {
			$chunks = explode($sep, $src);
			if ($glue) {
				$first = array_shift($chunks);
				$last = array_pop($res);
				$res[] = $last . $first;
			}
			$res = array_merge($res, $chunks);
		}

		return array_values(array_filter($res));
	}



	private function buildTree2($src, $offset, $open = null, $close = null)
	{
		$res = array();
		while ($src) {
			$tag = $this->findTag($src, $offset);
			$quote = TextParser::indexOfText($src, $offset);

			// text má přednost
			if ($tag && $quote && $quote[1] < $tag[0]) {
				$index = TextParser::indexOfQuotes($quote[0], $src, $quote[1] + 1);
				if ($index < 0) {
					break;
				}
				$offset = $index + 1;
			}
			else {
				switch (True) {
					// Žádný další tag
					case ($tag === False):
						$res[] = $src;
						return array(new Token($open, $res, $close), false);

					// Zanoření
					case (count($tag) === 3):
						if ($tag[0]) {
							$res[] = substr($src, 0, $tag[0]);
						}
						$src = substr($src, $tag[0] + strlen($tag[1]));
						$def = $this->buildTree2($src, 0, $tag[1], $tag[2]);
						list($tree, $tail) = $def;
						$res[] = $tree;
						$src = $tail;
						break;

					// Uzavření
					case (count($tag) === 2):
						//~ self::assertCloseTag($src, $tag[0], $tag[1]);
						if ($x = substr($src, 0, $tag[0])) {
							$res[] = $x;
						}
						$tail = substr($src, $tag[0] + strlen($close));
						if (empty($tail)) {
							$tail = false;
						}
						return array(new Token($open, $res, $close), $tail);
				}
			}
		}

		return array(new Token($open, $res, $close), false);
	}



	/**
	 * Nejbližší tag. Otevírací či zavírací.
	 * @return [abs-pos, open, close] |  [abs-pos, close] | false
	 */
	private function findTag($src, $offset) {
		$curr = false;
		foreach ($this->tags as $pair) {
			list($open, $close) = $pair;
			$index = strpos($src, $open, $offset);

			// tag je nalezen, a buď je to první nalezený, nebo je lepší jak první nalezený.
			if ($index !== false && ( ! $curr || ($index < $curr[0]))) {
				$curr = array($index, $open, $close);
			}

			// A co když je uzavírací tag ještě dříve?
			$index = strpos($src, $close, $offset);
			if ($index !== false && ( ! $curr || ($index < $curr[0]))) {
				$curr = array($index, $close);
			}
		}

		return $curr;
	}



	/**
	 * @param string
	 * @param int
	 * @param string
	 * PHP Parse error:  syntax error, unexpected ')' in /home/dell/Projects/php-utils.github/tests/Taco/Parsers/TokenizerTest.php on line 58
	 */
	private static function assertOpenTag($src, $pos, $tag)
	{
		if (substr($src, $pos, strlen($tag)) !== $tag) {
			$fragment = substr($src, -80);
			throw new RuntimeException("Cannot found open tag: `{$tag}' in fragment: `...{$fragment}'.");
		}
	}



	/**
	 * @param string
	 * @param int
	 * @param string
	 */
	private static function assertCloseTag($src, $pos, $tag)
	{
		if (substr($src, $pos, strlen($tag)) !== $tag) {
			$fragment = substr($src, -80);
			throw new RuntimeException("Cannot found close tag: `{$tag}' in fragment: `...{$fragment}'.");
		}
	}

}



class Token
{
	private $open;
	private $close;
	private $items;

	/**
	 * @param string $open Otevírací token. Například <tag>. Netýká se stringu. Otevírací tag může být opět token.
	 * @param array $items Mohou to být stringy, nebo další Tokeny. Tedy, vytváříme strom.
	 * @param string $close Uzavírací token jako </>
	 */
	function __construct($open, array $items, $close)
	{
		$this->open = $open;
		$this->close = $close;
		$this->items = $items;
	}



	function getItems()
	{
		return $this->items;
	}



	function getOpen()
	{
		return $this->open;
	}



	function getClose()
	{
		return $this->close;
	}



	function __toString()
	{
		return $this->open . implode('', $this->items) . $this->close;
	}

}
