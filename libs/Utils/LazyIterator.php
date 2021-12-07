<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Utils;


use Iterator,
	Countable;


/**
 * @template T
 * @implements Iterator<T>
 */
class LazyIterator implements Iterator, Countable
{
	/** @var array<T>|null */
	private $values = Null;

	/** @var int */
	private $pointer;

	/** @var callable */
	private $callback;


	/**
	 * @param callable $callback
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
		$this->pointer = 0;
	}



	/**
	 * Returns the key of the current element.
	 * @return int
	 */
	function key()
	{
		return $this->pointer;
	}



	/**
	 * Returns the current element.
	 * @return T
	 */
	function current()
	{
		$this->fetch();
		return $this->values[$this->pointer];
	}



	/**
	 * Moves forward to next element.
	 * @return void
	 */
	function next()
	{
		$this->pointer++;
	}



	/**
	 * Checks if there is a current element after calls to rewind() or next().
	 * @return bool
	 */
	function valid()
	{
		$this->fetch();
		return isset($this->values[$this->pointer]);
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
	 * @return array<T>
	 */
	private function fetch()
	{
		if (Null === $this->values) {
			$fce = $this->callback;
			// defragment key of sequence
			$this->values = array_values($fce());
		}
		return $this->values;
	}

}
