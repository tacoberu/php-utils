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
require_once __dir__ . '/../../../libs/Taco/Data/Right.php';


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




}
