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


namespace Taco\Data;


require_once __dir__ . '/../../../libs/Taco/Data/Either.php';
require_once __dir__ . '/../../../libs/Taco/Data/Left.php';


use PHPUnit_Framework_TestCase;


/**
 * @call phpunit --bootstrap ../../../../../bootstrap.php LeftTest.php
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
