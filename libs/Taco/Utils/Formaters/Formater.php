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
 * ?
 */
interface Formater
{

	/**
	 * Konfigurace formáteru.
	 * @param array
	 */
	function setOptions(array $opts);


	/**
	 * @return string
	 */
	function format($val);


}
