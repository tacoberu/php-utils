<?php

/**
 * Test třídy MoneyFormaterTest
 */

namespace Schoolpartner\Utils\Formaters;


use Nette;
use Tester,
	Tester\Assert;
use Schoolpartner\Data;


$container = require __dir__ . '/../../../../../bootstrap.php';



class MoneyFormaterTest extends Tester\TestCase
{


	public function testRemoveEmptyDecimal()
	{
		$val = new Data\Money('CZK', (float)123456789);
		$f = new MoneyFormater();
		Assert::equal("123 456 789", $f->format($val));
	}


	public function testPreserveEmptyDecimal()
	{
		$val = new Data\Money('CZK', (float)123456789);
		$f = new MoneyFormater();
		$f->setOptions(['preserveEmptyDecimal' => True]);
		Assert::equal("123 456 789,00", $f->format($val));
	}


	public function testWithDecimal()
	{
		$val = new Data\Money('CZK', 123456789.123456);
		$f = new MoneyFormater();
		Assert::equal("123 456 789,12", $f->format($val));
	}


	public function testWithCurrencyMark()
	{
		$val = new Data\Money('CZK', 123456789.123456);
		$f = new MoneyFormater();
		$f->setOptions(['useCurrency' => True]);
		Assert::equal("123 456 789,12 Kč", $f->format($val));
	}


	public function testWithCurrencyMarkUSD()
	{
		$val = new Data\Money('USD', 123456789.123456);
		$f = new MoneyFormater();
		$f->setOptions(['useCurrency' => True]);
		Assert::equal("$ 123 456 789,12", $f->format($val));
	}


	public function testWithCurrencyMarkEUR()
	{
		$val = new Data\Money('eUr', 123456789.123456);
		$f = new MoneyFormater();
		$f->setOptions(['useCurrency' => True]);
		Assert::equal("€ 123 456 789,12", $f->format($val));
	}


	public function testWithCurrencyMarkUnknow()
	{
		$val = new Data\Money('GBP', 123456789.123456);
		$f = new MoneyFormater();
		$f->setOptions(['useCurrency' => True]);
		Assert::equal("123 456 789,12", $f->format($val));
	}


}

id(new MoneyFormaterTest($container))->run();
