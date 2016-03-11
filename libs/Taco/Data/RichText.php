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

namespace Taco\Data;


use Nette;


interface RichText
{

	function getContent();

}


/**
 * Formátování je řešeno pomocí HTML.
 */
class HtmlRichText extends Nette\Object implements RichText
{

	private $content;


	static function createFrom($m)
	{
		return new self($m);
	}


	function __construct($content)
	{
		$this->content = $content;
	}


	function getContent()
	{
		return $this->content;
	}

}



/**
 * Formátování je řešeno pomocí Texy.
 */
class TexyRichText extends Nette\Object implements RichText
{

	private $content;


	static function createFrom($m)
	{
		return new self($m);
	}


	function __construct($content)
	{
		$this->content = $content;
	}


	function getContent()
	{
		return $this->content;
	}

}
