<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Parsers;

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
	 * Split to code and "te\"xt".
	 *
	 * @param string $src
	 * @return array of string
	 */
	static function mark($src)
	{
		$res = array();
		// Najdeme první uvozovku. Kod do ní zpracujeme.
		while ($quote = self::indexOfText($src)) {
			$res[] = substr($src, 0, $quote[1]);
			$src = substr($src, $quote[1]);

			// Najdeme druhou uvozovku.
			$index = self::indexOfQuotes($quote[0], $src, 1);
			if ($index < 0) {
				break;
			}
			$res[] = substr($src, 0, $index + 1);
			$src = substr($src, $index + 1);
		}

		if ($src) {
			$res[] = $src;
		}

		return array_values(array_filter($res));
	}



	static function isText($s)
	{
		if ($s[0] === '"' || $s[0] === "'") {
			return True;
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
	 * @return array | False Like [", abs-pos] | [', abs-pos] | False
	 */
	static function indexOfText($src, $offset = 0, $escapedstyle = self::ESCAPE_STYLE_BACKSLASH)
	{
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
	 * @return array|false Like [", abs-pos] | [', abs-pos] | False
	 */
	private static function findQuotes($quote, $escaped, $s, $offset = 0)
	{
		// @HACK - předpokládáme, že escapování je řešeno pomocí jednoho znaku. Což třeba v přípaě SQL delimiteru neplatí.
		// Ale dá se prohlásit, že v případě SQL delimiterů prostě neescapujeme.
		$escapedofescaped = $escaped[0];
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
