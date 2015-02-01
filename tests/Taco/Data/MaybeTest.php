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


require_once __dir__ . '/../../../libs/Taco/Data/Maybe.php';


use PHPUnit_Framework_TestCase;


/**
 * @call phpunit --bootstrap ../../../../../bootstrap.php MaybeTest.php
 */
class MaybeTest extends PHPUnit_Framework_TestCase
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
		$this->assertInstanceOf('Taco\Data\Just', $m);
		$this->assertInstanceOf('Taco\Data\Maybe', $m);
	}


	function testUnpackJust()
	{
		$this->assertEquals(42, Just::assert(new Just(42)));
	}


	function testUnpackJustFail()
	{
		$this->setExpectedException('RuntimeException', 'Maybe return Nothing.');
		Just::assert(new Nothing());
	}


	function testUnpackNothing()
	{
		$this->assertNull(Nothing::assert(new Nothing()));
	}


	function testUnpackNothingFail()
	{
		$this->setExpectedException('RuntimeException', 'Maybe return Just.');
		Nothing::assert(new Just(42));
	}

}
