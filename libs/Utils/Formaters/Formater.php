<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Utils\Formaters;


/**
 * Konfigurovatelné formátování hodnoty.
 */
interface Formater
{

	/**
	 * Konfigurace formáteru.
	 */
	function setOptions(array $opts);


	/**
	 * @return string
	 */
	function format($val);


}
