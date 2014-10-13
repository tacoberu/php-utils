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

namespace Taco\Data;


use RuntimeException;

/**
 * The Either type represents values with two possibilities: a value of type
 * Either a b is either Left a or Right b.
 *
 * The Either type is sometimes used to represent a value which is either
 * correct or an error; by convention, the Left constructor is used to
 * hold an error value and the Right constructor is used to hold a
 * correct value (mnemonic: "right" also means "correct").
 *
 * @inspired by Haskell Data-Either
 */
class Right implements Either
{

	private $value;

	/**
	 * @param mixin
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}



	/**
	 * @return mixin
	 */
	public function getValue()
	{
		return $this->value;
	}



	public static function assert(Either $either)
	{
		if ($either instanceof Right) {
			return $either->getValue();
		}
		throw new RuntimeException($either->getMessage(), $either->getCode());
	}

}
