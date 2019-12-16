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


	/** @var closure */
	private $formater;


	/**
	 * Constructor injection.
	 */
	function __construct($closure)
	{
		$this->formater = $closure;
	}



	/**
	 * Konfigurace formáteru.
	 * @param array
	 */
	function setOptions(array $opts)
	{}



	/**
	 * Render cell
	 * @param mixed $value
	 * @return string
	 */
	function format($val)
	{
		$closure = $this->formater;
		return $closure($val);
	}


}
