<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */


namespace Taco\Utils\Formaters;

require_once __dir__ . '/../../../libs/Utils/Formaters/DateTimeFormater.php';


use PHPUnit_Framework_TestCase;
use DateTime;


/**
 * @call phpunit DateTimeFormaterTest.php
 */
class DateTimeFormaterTest extends PHPUnit_Framework_TestCase
{


	function testDefaultFormat()
	{
		$f = new DateTimeFormater();
		$d = DateTime::createFromFormat('Y-m-d H:i:s', '1999-06-30 14:57:21');
		$this->assertEquals('1999-06-30 14:57:21', $f->format($d));
	}



	function testSetFormatInConstruct()
	{
		$f = new DateTimeFormater('j.n.Y, G:i');
		$d = DateTime::createFromFormat('Y-m-d H:i:s', '1999-06-30 14:57:21');
		$this->assertEquals('30.6.1999, 14:57', $f->format($d));
	}



	function testSetFormatInOptions()
	{
		$f = new DateTimeFormater();
		$f->setOptions(array('j.n.Y, G:i'));
		$d = DateTime::createFromFormat('Y-m-d H:i:s', '1999-06-30 14:57:21');
		$this->assertEquals('30.6.1999, 14:57', $f->format($d));
	}



	function testEmptyValue()
	{
		$f = new DateTimeFormater();
		$this->assertEquals('-', $f->format(Null));
	}



	function testSetFormatEmptyValueInConstruct()
	{
		$f = new DateTimeFormater('j.n.Y, G:i', 'x');
		$this->assertEquals('x', $f->format(Null));
	}



	function testSetFormatEmptyValueInOptions()
	{
		$f = new DateTimeFormater();
		$f->setOptions(array('j.n.Y, G:i', 'x'));
		$this->assertEquals('x', $f->format(Null));
	}



	function testInvalidFormat()
	{
		$this->setExpectedException('InvalidArgumentException', "Argument must be type of DateTime.");

		$f = new DateTimeFormater();
		$f->format(123);
	}



	function testSetFormatPreserveSpace()
	{
		$f = new DateTimeFormater();
		$f->setOptions(array('j.n.Y, G:i', '-', True));
		$d = DateTime::createFromFormat('Y-m-d H:i:s', '1999-06-01 4:57:21');
		$this->assertEquals(' 1. 6.1999,  4:57', $f->format($d));
		$f->setOptions(array('G:i, j.n.Y'));
		$this->assertEquals(' 4:57,  1. 6.1999', $f->format($d));
	}



	function testTryParse()
	{
		$f = new DateTimeFormater();
		$this->assertEquals('1999-06-30 14:57:21', $f->format('1999-06-30 14:57:21'));
	}

}
