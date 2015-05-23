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


	/**
	 * Vyhledá konec řetězce. Bere v potaz escapování.
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


}
