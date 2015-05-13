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


use RuntimeException,
	LogicExeption;


/**
 * The Maybe type encapsulates an optional value. A value of type Maybe
 * a either contains a value of type a (represented as Just a), or it is
 * empty (represented as Nothing). Using Maybe is a good way to deal with
 * errors or exceptional cases without resorting to drastic measures such
 * as error.
 *
 * @inspired by Haskell Data-Maybe
 */
interface Maybe
{

	/**
	 * if this is Just, then call callback, otherwise return nothing.
	 * @return Maybe
	 */
	function ifJust($callback);

	/**
	 * for Just return value, for nothing return NULL.
	 */
	function getValue();
}



class Just implements Maybe
{

	private $value;


	/**
	 * @param mixin
	 */
	function __construct($value)
	{
		$this->value = $value;
	}



	/**
	 * @return mixin
	 */
	function getValue()
	{
		return $this->value;
	}



	/**
	 * @return Maybe
	 */
	function ifJust($callback)
	{
		$val = $callback($this->getValue());
		if ($val instanceof Maybe) {
			return $val;
		}
		throw new LogicExeption("ifJust callback must return Maybe.");
	}



	static function assert(Maybe $maybe)
	{
		if ($maybe instanceof Just) {
			return $maybe->getValue();
		}
		throw new RuntimeException("Maybe return Nothing.", 1);
	}

}




class Nothing implements Maybe
{


	/**
	 * @return Maybe
	 */
	function ifJust($_)
	{
		return $this;
	}


	/**
	 * @return Null
	 */
	function getValue()
	{
		return Null;
	}



	static function assert(Maybe $maybe)
	{
		if ($maybe instanceof Just) {
			throw new RuntimeException("Maybe return Just.", 1);
		}
	}

}


class MaybeUtils
{

	static function ifTrue($expr)
	{
		if ($expr) {
			return new Just($expr);
		}
		return new Nothing();
	}
}
