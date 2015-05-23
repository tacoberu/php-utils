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



	function testLookupEndIndexEmpty()
	{
		$this->assertFalse($this->parser->lookupEndIndex('', '"'));
	}



	function testLookupEndIndexShort()
	{
		$this->assertEquals(0, $this->parser->lookupEndIndex('"', '"'));
	}



	function testLookupEndIndexWithoutEnd()
	{
		$this->assertFalse($this->parser->lookupEndIndex('abc def', '"'));
	}



	function testLookupEndIndexSample()
	{
		$this->assertEquals(7, $this->parser->lookupEndIndex('abc def"', '"'));
	}



	function testLookupEndWithEscaped()
	{
		$source = "a bc def 123 'pi' \\\"sss \"da next tex";
		$this->assertEquals(24, $this->parser->lookupEndIndex($source, '"'));
	}

}
