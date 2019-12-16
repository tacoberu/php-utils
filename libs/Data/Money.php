<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

use Nette,
	Nette\Utils\Validators;


/**
 * Reprezentace částky, která ssebou nese i informace o měně.
 * @credits SchoolPartner
 */
class Money extends Nette\Object
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
	 * @param string CZK, EUR, USD
	 * @param float|int|numeric
	 */
	function __construct($currency, $value)
	{
		$currency = strtoupper($currency);
		Validators::assert($currency, 'string:3');
		Validators::assert($value, 'numeric');
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


	/**
	 * @return float
	 */
	function setValue($m)
	{
		Validators::assert($m, 'numeric');
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

}
