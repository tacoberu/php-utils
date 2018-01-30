<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Utils;

use PHPUnit_Framework_TestCase;


/**
 * @call phpunit LazyIteratorTest.php
 */
class LazyIteratorTest extends PHPUnit_Framework_TestCase
{

	function testMinimal()
	{
		$iter = new LazyIterator(function() {
			return [];
		});
		$this->assertSame([], iterator_to_array($iter));
	}



	function testTypical()
	{
		$iter = new LazyIterator(function() {
			return [2, 8, 8];
		});
		$this->assertSame([2, 8, 8], iterator_to_array($iter));
	}



	function testKeepKeys()
	{
		$pattern = ['a' => 2, 'b' => 8, 'q' => False, 'x' => 8, 'z' => True];
		$iter = new LazyIterator(function() use ($pattern) {
			return $pattern;
		});
		$this->assertSame($pattern, iterator_to_array($iter));
	}



	function testConcrete()
	{
		$iter = new LazyIterator(function() {
			return ['a' => 'A', 'b' => false, 'c' => 'C'];
		});
		$iter->rewind();
		$this->assertSame('a', $iter->key());
		$this->assertSame('A', $iter->current());
		$iter->next();
		$this->assertSame('b', $iter->key());
		$this->assertSame(false, $iter->current());
		$iter->next();
		$this->assertSame('c', $iter->key());
		$this->assertSame('C', $iter->current());
		$iter->next();
		$this->assertNull($iter->key());
		$this->assertFalse($iter->current());
		$iter->rewind();
		$this->assertSame('a', $iter->key());
		$this->assertSame('A', $iter->current());
	}

}
