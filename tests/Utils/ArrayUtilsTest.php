<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Utils;

use PHPUnit_Framework_TestCase;


/**
 * @call phpunit ArrayUtilsTest.php
 */
class ArrayUtilsTest extends PHPUnit_Framework_TestCase
{

	function testCartesianProductEmpty()
	{
		$this->assertEquals(array(), ArrayUtils::cartesianProduct(array()));
	}



	function testCartesianProductUne()
	{
		$this->assertEquals(array(array('A')), ArrayUtils::cartesianProduct(str_split('A')));
	}



	function testCartesianProductDoublet()
	{
		$this->assertEquals(array(
			array('A', 'B'),
			array('B', 'A'),
		), ArrayUtils::cartesianProduct(str_split('AB')));
	}



	function testCartesianProductTriplet()
	{
		$this->assertEquals(array(
			array('A', 'B', 'C'),
			array('A', 'C', 'B'),
			array('B', 'A', 'C'),
			array('B', 'C', 'A'),
			array('C', 'A', 'B'),
			array('C', 'B', 'A'),
		), ArrayUtils::cartesianProduct(str_split('ABC')));
	}

}
