<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

use PHPUnit\Framework\TestCase;
use DateTime as PhpDateTime;


class DateTimeRangeTest extends TestCase
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
