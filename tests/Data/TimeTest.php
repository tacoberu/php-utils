<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

use PHPUnit\Framework\TestCase;
use DateTime as PhpDateTime;
use InvalidArgumentException;


class TimeTest extends TestCase
{


	function testDefault()
	{
		$pattern = date('H:i:s');
		$t = new Time();
		$this->assertEquals($pattern, $t->format('H:i:s'));
	}



	function testDefaultNull()
	{
		$t = new Time(0, 0, 12);
		$this->assertEquals('00:00:12', $t->format('H:i:s'));
	}



	function testCreate()
	{
		$t = new Time(9, 2, 3);
		$this->assertEquals('09:02:03', $t->format('H:i:s'));
		$this->assertEquals('09:02', $t->format('H:i'));
		$this->assertEquals('AM 9:02', $t->format('A G:i'));
		$this->assertEquals('am 9:02', $t->format('a G:i'));
	}



	function testParse()
	{
		$t = Time::createFromFormat('H:i:s', '09:12:07');
		$this->assertEquals('9-12-07', $t->format('G-i-s'));
	}



	function testInvalidParseHours()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Hours `25' is invalid in range 0 - 23.");
		new Time(25, 70, 90);
	}



	function testInvalidParseMinutes()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Minutes `70' is invalid in range 0 - 59.");
		new Time(5, 70, 0);
	}



	function testInvalidParseSeconds()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Seconds `90' is invalid in range 0 - 59.");
		new Time(2, 0, 90);
	}



	function testInvalidParse()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("A four digit year could not be found; Data missing: `aabbcc'.");
		Time::createFromFormat('Y-m-d', 'aabbcc');
	}



	function testInvalidFormat2()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("The separation symbol could not be found; Unexpected data found.; Trailing data: `2011x02x02'.");
		Time::createFromFormat('H-i-s', '2011x02x02');
	}



	function testInvalidFormat3()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("The parsed time was invalid: `99:04:31'.");
		Time::createFromFormat('H:i:s', '99:04:31');
	}


}
