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


namespace Taco\Parsers;


require_once __dir__ . '/../../../vendor/autoload.php';
require_once __dir__ . '/../../../libs/Taco/Parsers/TextParser.php';


use PHPUnit_Framework_TestCase;
use Nette;



/**
 * @call phpunit TextParserTest.php
 */
class TextParserTest extends PHPUnit_Framework_TestCase
{

	private $parser;


	function setUp()
	{
		$this->parser = new TextParser();
	}

/*

	function testLookupEndQuoteIndexEmpty()
	{
		$this->assertFalse($this->parser->lookupEndIndex('', '"'));
	}



	function testLookupEndQuoteIndexShort()
	{
		$this->assertEquals(0, $this->parser->lookupEndIndex('"', '"'));
	}



	function testLookupEndQuoteIndexWithoutEnd()
	{
		$this->assertFalse($this->parser->lookupEndIndex('abc def', '"'));
	}



	function testLookupEndQuoteIndexSample()
	{
		$this->assertEquals(7, $this->parser->lookupEndIndex('abc def"', '"'));
	}



	function testLookupEndQuoteWithEscapedQuote()
	{
		$source = "a bc def 123 'pi' \\\"sss \"da next tex";
		$this->assertEquals(24, $this->parser->lookupEndIndex($source, '"'));
	}


	function testLookupEndBracketIndexEmpty()
	{
		$this->assertFalse($this->parser->lookupEndBracketIndex(''));
	}



	function testLookupEndBracketIndexShort()
	{
		$this->assertEquals(0, $this->parser->lookupEndBracketIndex(')'));
	}



	function testLookupEndBracketIndexWithoutEnd()
	{
		$this->assertFalse($this->parser->lookupEndBracketIndex('abc def', '"'));
	}



	function testLookupEndBracketIndexSample()
	{
		$this->assertEquals(7, $this->parser->lookupEndBracketIndex('abc def)'));
	}
*/

	function testLookupEndBracketIndexA()
	{
		$this->assertEquals(15, $this->parser->lookupEndBracketIndex('abc (defx) ddd) vvv'));
		$this->assertEquals(19, $this->parser->lookupEndBracketIndex('abc (defx) (bb)ddd) vvv'));
	}


}
