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
	 * @return void
	 */
	function setOptions(array $opts);


	/**
	 * @param mixed $val
	 * @return string
	 */
	function format($val);


}
