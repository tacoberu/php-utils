<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */


namespace Taco\Data;

require_once __dir__ . '/../../libs/Data/Either.php';
require_once __dir__ . '/../../libs/Data/Left.php';


use PHPUnit_Framework_TestCase;


/**
 * @call phpunit --bootstrap ../../../../bootstrap.php LeftTest.php
 */
class LeftTest extends PHPUnit_Framework_TestCase
{


	function testEmptyValue()
	{
		$m = new Left(Null);
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
		$m = new Left(Null);
		$this->assertInstanceOf('Taco\Data\Left', $m);
		$this->assertInstanceOf('Taco\Data\Either', $m);
	}



	function assertEqualsState($msg, $code, $m)
	{
		$this->assertEquals($msg, $m->getMessage());
		$this->assertEquals($code, $m->getCode());
	}

}
