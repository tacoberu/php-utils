<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

use PHPUnit_Framework_TestCase;
use DateTime as PhpDateTime;


class MoneyTest extends PHPUnit_Framework_TestCase
{


	function testDefault()
	{
		$inst = new Money("czk", 11.1);
		$this->assertSame('CZK', $inst->getCurrency());
		$this->assertSame(11.1, $inst->getValue());

		$inst->setValue(44);
		$this->assertSame(44.0, $inst->getValue());
	}



}
