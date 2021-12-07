<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Utils\Formaters;


/**
 * Plain text column
 */
class NullFormater implements Formater
{


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
		return (string)$val;
	}


}
