<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

use InvalidArgumentException;


/**
 * Reprezentace částky, která ssebou nese i informace o měně.
 * @credits SchoolPartner
 */
class Money
{

	/**
	 * Typ měny, koruna, dolar, euro.
	 * @var string
	 */
	private $currency;


	/**
	 * Hodnota částky
	 * @var float
	 */
	private $value;


	/**
	 * @param string $currency CZK, EUR, USD
	 * @param float|int|numeric $value
	 */
	function __construct($currency, $value)
	{
		if (!is_string($currency)) {
			throw new InvalidArgumentException("Argument currency must be string, like CZK, EUR, USD.");
		}
		if (strlen($currency) !== 3) {
			throw new InvalidArgumentException("Argument currency must be 3chars len string, like CZK, EUR, USD.");
		}
		if (!self::isNumeric($value)) {
			throw new InvalidArgumentException("Argument value must be numeric, like `4`, `4.1`, `-4.5`.");
		}
		$currency = strtoupper($currency);
		$this->currency = $currency;
		$this->value = (float)$value;
	}


	/**
	 * @return string CZK, EUR, USD
	 */
	function getCurrency()
	{
		return $this->currency;
	}


	function setValue($m)
	{
		if (!self::isNumeric($m)) {
			throw new InvalidArgumentException("Argument value must be numeric, like `4`, `4.1`, `-4.5`.");
		}
		$this->value = (float) $m;
		return $this;
	}


	/**
	 * @return float
	 */
	function getValue()
	{
		return $this->value;
	}



	/**
	 * Finds whether a string is a floating point number in decimal base.
	 * @credits David Grudl (https://davidgrudl.com)
	 * @return bool
	 */
	private static function isNumeric($value)
	{
		return is_float($value) || is_int($value) || is_string($value) && preg_match('#^-?[0-9]*[.]?[0-9]+\z#', $value);
	}

}
