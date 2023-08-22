<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

use PHPUnit\Framework\TestCase;
use RuntimeException;


class MaybeTest extends TestCase
{


	function testEmptyValue()
	{
		$m = new Just(Null);
		$this->assertEquals(Null, $m->getValue());
	}


	function testNumValue()
	{
		$m = new Just(42);
		$this->assertEquals(42, $m->getValue());
	}


	function testTypeContract()
	{
		$m = new Just(Null);
		$this->assertInstanceOf(Just::class, $m);
		$this->assertInstanceOf(Maybe::class, $m);
	}


	function testUnpackJust()
	{
		$this->assertEquals(42, Just::assert(new Just(42)));
	}


	function testUnpackJustFail()
	{
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Maybe return Nothing.');
		Just::assert(new Nothing());
	}


	function testUnpackNothing()
	{
		$this->assertNull(Nothing::assert(new Nothing()));
	}


	function testUnpackNothingFail()
	{
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Maybe return Just.');
		Nothing::assert(new Just(42));
	}

}
