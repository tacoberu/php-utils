<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

require_once __dir__ . '/../../libs/Data/DateTimeRange.php';


use PHPUnit_Framework_TestCase;
use DateTime as PhpDateTime;


/**
 * @call phpunit --bootstrap ../../../../bootstrap.php DateTimeRangeTest.php
 */
class DateTimeRangeTest extends PHPUnit_Framework_TestCase
{


	function testCorrect()
	{
		$from = DateTime::createFromFormat('Y-m-d', '1999-06-30');
		$to = DateTime::createFromFormat('Y-m-d', '1999-09-30');
		$range = new DateTimeRange($from, $to);
		$this->assertEquals($from, $range->getFrom());
		$this->assertEquals($to, $range->getTo());
	}



}
