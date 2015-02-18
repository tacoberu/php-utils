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
