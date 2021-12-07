<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

use PHPUnit_Framework_TestCase;


class LeftTest extends PHPUnit_Framework_TestCase
{


	function testEmptyValue()
	{
		$m = new Left(Null);// @phpstan-ignore-line
		$this->assertEqualsState(Null, 0, $m);
	}



	function testNumValue()
	{
		$m = new Left("ahoj");
		$this->assertEqualsState("ahoj", 0, $m);
	}



	function testNumValueWithCode()
	{
		$m = new Left("ahoj", 4);
		$this->assertEqualsState("ahoj", 4, $m);
	}



	function testMakeFromException()
	{
		$m = Left::fromException(new \Exception("abc", 4));
		$this->assertEqualsState("abc", 4, $m);
	}



	function testTypeContract()
	{
		$m = new Left(Null);// @phpstan-ignore-line
		$this->assertInstanceOf('Taco\Data\Left', $m);
		$this->assertInstanceOf('Taco\Data\Either', $m);
	}



	function assertEqualsState($msg, $code, $m)
	{
		$this->assertEquals($msg, $m->getMessage());
		$this->assertEquals($code, $m->getCode());
	}

}
