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
	 * @param array<mixed> $opts
	 */
	function setOptions(array $opts) : void;


	/**
	 * @param mixed $val
	 */
	function format($val) : string;


}
