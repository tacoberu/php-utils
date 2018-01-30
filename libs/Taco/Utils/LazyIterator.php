<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Utils;

use Iterator,
	Countable;


class LazyIterator implements Iterator, Countable
{
	/** @var array */
	private $values = Null;

	/** @var callback */
	private $callback;


	/**
	 * @param callback
	 */
	function __construct($callback)
	{
		$this->callback = $callback;
	}



	/**
	 * Rewinds the iterator to the first element.
	 * @return void
	 */
	function rewind()
	{
		if ($this->values === Null) {
			return;
		}
		reset($this->values);
	}



	/**
	 * Returns the key of the current element.
	 * @return mixed
	 */
	function key()
	{
		$this->fetch();
		return key($this->values);
	}



	/**
	 * Returns the current element.
	 * @return mixed
	 */
	function current()
	{
		$this->fetch();
		return current($this->values);
	}



	/**
	 * Moves forward to next element.
	 * @return void
	 */
	function next()
	{
		$this->fetch();
		next($this->values);
	}



	/**
	 * Checks if there is a current element after calls to rewind() or next().
	 * @return bool
	 */
	function valid()
	{
		return array_key_exists($this->key(), $this->values);
	}



	/**
	 * Required by the Countable interface.
	 * @return int
	 */
	function count()
	{
		$this->fetch();
		return count($this->values);
	}



	/**
	 * Lazy fetches data from callback.
	 */
	private function fetch()
	{
		if (Null === $this->values) {
			$fce = $this->callback;
			// defragment key of sequence
			//~ $this->values = array_values($fce());
			$this->values = $fce();
		}
		return $this->values;
	}

}
