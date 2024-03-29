<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

use RuntimeException,
	LogicException;


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


	function __construct($value)
	{
		$this->value = $value;
	}



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
		throw new LogicException("ifJust callback must return Maybe.");
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
