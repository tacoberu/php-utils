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

namespace Taco\Utils;


use Iterator,
	Countable;


/**
 * Decorate of content.
 */
class LazyIterator implements Iterator, Countable
{
	/** @var array */
	private $values = Null;

	/** @var int */
	private $pointer;

	/** @var ??? */
	private $callback;


	/**
	 * @param  DibiResult
	 */
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
