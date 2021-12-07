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


	function setOptions(array $opts)
	{}


	function format($val)
	{
		return (string)$val;
	}


}
