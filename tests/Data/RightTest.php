<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

use PHPUnit\Framework\TestCase;
use RuntimeException;


class RightTest extends TestCase
{


	function testEmptyValue()
	{
		$m = new Right(Null);
		$this->assertEquals(Null, $m->getValue());
	}


	function testNumValue()
	{
		$m = new Right(42);
		$this->assertEquals(42, $m->getValue());
	}


	function testTypeContract()
	{
		$m = new Right(Null);
		$this->assertInstanceOf(Right::class, $m);
		$this->assertInstanceOf(Either::class, $m);
	}


	function testUnpack()
	{
		$m = new Right(42);
		$this->assertEquals(42, Right::assert($m));
	}


	function testUnpackFail()
	{
		$this->expectException(RuntimeException::class);
		$m = new Left('foo', 42);
		$this->assertEquals(42, Right::assert($m));
	}

}
