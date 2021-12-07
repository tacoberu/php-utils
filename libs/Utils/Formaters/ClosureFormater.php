<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Utils\Formaters;


/**
 * Formátování zabalené do funkce.
 */
class ClosureFormater implements Formater
{


	/** @var callable */
	private $formater;


	/**
	 * @param callable $closure
	 */
	function __construct($closure)
	{
		$this->formater = $closure;
	}



	/**
	 * Konfigurace formáteru.
	 */
	function setOptions(array $opts)
	{}



	/**
	 * Render cell
	 * @return string
	 */
	function format($val)
	{
		$cb = $this->formater;
		return $cb($val);
	}


}
