<?php
/**
 * Copyright (c) since 2004 Martin Takáč (http://martin.takac.name)
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace Taco\Data;


use Nette;
use Nette\Utils\Validators;


interface RichText
{

	/**
	 * @return string
	 */
	function getContent();

}



/**
 * Formátování je řešeno pomocí HTML.
 */
class HtmlRichText extends Nette\Object implements RichText
{

	/**
	 * @var string
	 */
	private $content;


	/**
	 * @param string
	 */
	static function createFrom($m)
	{
		return new self($m);
	}



	/**
	 * @param string
	 */
	function __construct($content)
	{
		Validators::assert($content, 'string');
		$this->content = $content;
	}



	/**
	 * @return string
	 */
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

	/**
	 * @var string
	 */
	private $content;


	/**
	 * @param string
	 */
	static function createFrom($m)
	{
		return new self($m);
	}


	/**
	 * @param string $content
	 */
	function __construct($content)
	{
		Validators::assert($content, 'string');
		$this->content = $content;
	}



	/**
	 * @return string
	 */
	function getContent()
	{
		return $this->content;
	}

}
