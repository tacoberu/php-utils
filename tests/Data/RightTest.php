<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

require_once __dir__ . '/../../libs/Data/Either.php';
require_once __dir__ . '/../../libs/Data/Right.php';


use PHPUnit_Framework_TestCase;


/**
 * @call phpunit --bootstrap ../../../../../bootstrap.php RightTest.php
 */
class RightTest extends PHPUnit_Framework_TestCase
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
		$this->assertInstanceOf('Taco\Data\Right', $m);
		$this->assertInstanceOf('Taco\Data\Either', $m);
	}


	function testUnpack()
	{
		$m = new Right(42);
		$this->assertEquals(42, Right::assert($m));
	}


	function testUnpackFail()
	{
		$this->setExpectedException('RuntimeException', "foo", 42);
		$m = new Left('foo', 42);
		$this->assertEquals(42, Right::assert($m));
	}

}
