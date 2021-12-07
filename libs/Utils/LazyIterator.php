<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Utils;


use Iterator,
	Countable;


/**
 * Decorate of content.
 */
class LazyIterator implements Iterator, Countable
{
	/** @var array|null */
	private $values = Null;

	/** @var int */
	private $pointer;

	private $callback;


	public function __construct($callback)
	{
		$this->callback = $callback;
	}



	/**
	 * Rewinds the iterator to the first element.
	 * @return void
	 */
	public function rewind()
	{
		$this->pointer = 0;
	}



	/**
	 * Returns the key of the current element.
	 * @return mixed
	 */
	public function key()
	{
		return $this->pointer;
	}



	/**
	 * Returns the current element.
	 * @return mixed
	 */
	public function current()
	{
		$this->fetch();
		return $this->values[$this->pointer];
	}



	/**
	 * Moves forward to next element.
	 * @return void
	 */
	public function next()
	{
		$this->pointer++;
	}



	/**
	 * Checks if there is a current element after calls to rewind() or next().
	 * @return bool
	 */
	public function valid()
	{
		$this->fetch();
		return isset($this->values[$this->pointer]);
	}



	/**
	 * Required by the Countable interface.
	 * @return int
	 */
	public function count()
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
			$this->values = array_values($fce());
		}
		return $this->values;
	}

}
