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


class TextParser
{


	private static $BRACKET_MAP = array(
			'(' => ')',
			'[' => ']',
			'{' => '}',
			);



	/**
	 * Vyhledá konec řetězce. Bere v potaz escapování.
	 * Předpokládá se, že řetězec je otevřený, tedy, že na indexu -1 byla
	 * uvozovka poznačená v $char
	 *
	 * @param string
	 * @param char
	 * @return int
	 */
	function lookupEndIndex($source, $char)
	{
		$index = 0;
		while(($index = strpos($source, $char, $index)) !== False) {
			if ($index === 0 || $source{$index-1} !== '\\') {
				return $index;
			}
			$index++;
		}
		return False;
	}



	/**
	 * Vyhledá konec výrazu ukončený závorkou. Počítá otevírací závorky.
	 * Předpokládá se, že výraz je otevřený, tedy, že na indexu -1 byla otevírací závorka.
	 *
	 * @param string
	 * @param char
	 * @return int
	 */
	function lookupEndBracketIndex($source, $bracket = ')')
	{
		$index = 0;
		while ($xs = Utils\Strings::match($source, '~[\(\)\{\}\[\]]~is', PREG_OFFSET_CAPTURE, $index)) {
			if ($xs[0][0] == $bracket) {
				return $xs[0][1] + 1;
			}
			$index = $xs[0][1];
			if (($shift = $this->lookupEndBracketIndex(substr($source, $index + 1), self::$BRACKET_MAP[$xs[0][0]])) === False) {
				return False;
			}
			$index += $shift + 1;
		}
		return False;
	}


}
