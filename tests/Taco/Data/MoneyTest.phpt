<?php

/**
 * Test třídy MoneyTest
 */

namespace Schoolpartner\Data;


use Nette;
use Tester,
	Tester\Assert;
use Schoolpartner\Data;


$container = require __dir__ . '/../../../../bootstrap.php';



class MoneyTest extends Tester\TestCase
{


	public function testCzk()
	{
		$val = new Data\Money('CZK', (float)123456789);
		Assert::equal("CZK", $val->currency);
		Assert::equal(123456789.0, $val->value);
	}



	public function testEur()
	{
		$val = new Data\Money('Eur', (float)12345.6789);
		Assert::equal("EUR", $val->currency);
		Assert::equal(12345.6789, $val->value);
	}


	public function testNumeric()
	{
		$val = new Data\Money('Eur', '12345.6789');
		Assert::equal(12345.6789, $val->value);
	}

	public function testNumericInt()
	{
		$val = new Data\Money('Eur', '12345');
		Assert::equal(12345.0, $val->value);
	}

}

id(new MoneyTest($container))->run();
