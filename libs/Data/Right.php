<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
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
