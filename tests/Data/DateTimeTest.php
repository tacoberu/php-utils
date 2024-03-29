<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

require_once __dir__ . '/../../libs/Data/DateTime.php';


use PHPUnit_Framework_TestCase;
use DateTime as PhpDateTime;


/**
 * @call phpunit --bootstrap ../../../../bootstrap.php ValueTest.php
 */
class DateTimeTest extends PHPUnit_Framework_TestCase
{


	function testCorrect()
	{
		$d = DateTime::createFromFormat('Y-m-d', '1999-06-30');
		$this->assertEquals('1999-06-30', $d->format('Y-m-d'));
	}


	function testInvalidFormat1()
	{
		$this->setExpectedException('InvalidArgumentException', "A four digit year could not be found; Data missing: `aabbcc'.");
		DateTime::createFromFormat('Y-m-d', 'aabbcc');
	}


	function testInvalidFormat2()
	{
		$this->setExpectedException('InvalidArgumentException', "Unexpected data found: `2011x02x02'.");
		DateTime::createFromFormat('Y-m-d', '2011x02x02');
	}


	function testInvalidFormat3()
	{
		$this->setExpectedException('InvalidArgumentException', "The parsed date was invalid: `1999-04-31'.");

		DateTime::createFromFormat('Y-m-d', '1999-04-31');
	}



}
