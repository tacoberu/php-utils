<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Parsers;

use Nette\Utils,
	Nette\Reflection;
use RuntimeException,
	InvalidArgumentException;


class TextParser
{

	const QUOTE      = "\x22";
	const APOSTROPHE = "\x27";
	const BACKSLASH  = "\x5c";

	const ESCAPE_STYLE_NONE = null;
	const ESCAPE_STYLE_BACKSLASH = 1;
	const ESCAPE_STYLE_DOUBLE = 2;


	/**
	 * @deprecated
	 *
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
	 * @deprecated
	 *
	 * Vyhledá konec výrazu ukončený závorkou. Počítá otevírací závorky.
	 * Předpokládá se, že výraz je otevřený, tedy, že na indexu -1 byla otevírací závorka.
	 *
	 * @param string
	 * @param char
	 * @return int
	 */
	function lookupEndBracketIndex($source, $bracket = ')')
	{
		$BRACKET_MAP = array(
			'(' => ')',
			'[' => ']',
			'{' => '}',
		);
		$index = 0;
		while ($xs = Utils\Strings::match($source, '~[\(\)\{\}\[\]]~is', PREG_OFFSET_CAPTURE, $index)) {
			if ($xs[0][0] == $bracket) {
				return $xs[0][1] + 1;
			}
			$index = $xs[0][1];
			if (($shift = $this->lookupEndBracketIndex(substr($source, $index + 1), $BRACKET_MAP[$xs[0][0]])) === False) {
				return False;
			}
			$index += $shift + 1;
		}
		return False;
	}



	/**
	 * Hledáme uvozovku (určenou prvním argumentem). Zohledňujeme escapování. Způsob escapování určujeme explicitně.
	 * @return int
	 */
	static function indexOfQuotes($quote, $s, $offset = 0, $escapedstyle = self::ESCAPE_STYLE_BACKSLASH)
	{
		switch ($escapedstyle) {
			case self::ESCAPE_STYLE_BACKSLASH:
				$escaped = self::BACKSLASH . $quote;
				break;
			case self::ESCAPE_STYLE_DOUBLE:
				$escaped = $quote . $quote;
				break;
			default:
				throw new InvalidArgumentException("Unsupported style of escaping.");
		}
		$res = self::findQuotes($quote, $escaped, $s, $offset);
		if ($res === False) {
			return -1;
		}
		return $res[1];
	}



	/**
	 * Nejbližší neescapovaná uvozovka nebo apostrof.
	 * @return [", abs-pos] | [', abs-pos] | False
	 */
	static function indexOfText($src, $offset = 0, $escapedstyle = self::ESCAPE_STYLE_BACKSLASH) {
		$a = self::indexOfQuotes(self::QUOTE,      $src, $offset, $escapedstyle);
		$b = self::indexOfQuotes(self::APOSTROPHE, $src, $offset, $escapedstyle);
		if ($a >= 0 && $b >= 0) {
			return $a > $b ? array(self::APOSTROPHE, $b) : array(self::QUOTE, $a);
		}

		if ($a >= 0) {
			return array(self::QUOTE, $a);
		}

		if ($b >= 0) {
			return array(self::APOSTROPHE, $b);
		}

		return False;
	}



	/**
	 * Hledáme uvozovku (určenou prvním argumentem). Způsob escapování určujeme explicitně.
	 * @return [", abs-pos] | [', abs-pos] | False
	 */
	private static function findQuotes($quote, $escaped, $s, $offset = 0)
	{
		// @HACK - předpokládáme, že escapování je řešeno pomocí jednoho znaku. Což třeba v přípaě SQL delimiteru neplatí.
		// Ale dá se prohlásit, že v případě SQL delimiterů prostě neescapujeme.
		$escapedofescaped = $escaped{0};
		$pos = strpos($s, $quote,   $offset);
		$esc = strpos($s, $escaped, $offset);

		// Ověřit, zda není escapované escapování. Což může být kumulativní.
		if ($esc !== False) {
			$use = True;
			for ($i = $esc - 1; $i >= 0; $i--) {
				if ($s[$i] !== $escapedofescaped) {
					break;
				}
				$use = ! $use;
			}
			if ( ! $use) {
				$esc = False;
			}

			$i += 1; // posunout index na začátek

			// Nejdřív se musím posunout za escapované escapování.
			while (True) {
				$i += 2;
				$x = strpos($s, $escapedofescaped . $escapedofescaped, $i);
				if ($x === False) {
					break;
				}
			}
			$pos = strpos($s, $quote,   $i);
			$esc = strpos($s, $escaped, $i);
		}

		// Escapovaná uvozovka.
		if ($esc !== False && $pos !== False && $esc <= $pos) {
			return self::findQuotes($quote, $escaped, $s, $pos + strlen($escaped));
		}

		if ($pos !== False) {
			return array($quote, $pos);
		}

		return False;
	}

}
