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


require_once __dir__ . '/../../../libs/Taco/Data/DateTimeRange.php';


use PHPUnit_Framework_TestCase;
use DateTime as PhpDateTime;


/**
 * @call phpunit --bootstrap ../../../../../bootstrap.php DateTimeRangeTest.php
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
