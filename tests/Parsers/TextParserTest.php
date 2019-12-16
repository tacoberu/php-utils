<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Parsers;

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



	/**
	 * @dataProvider dataFindQuotesBackslash
	 */
	function testIndexOfQuotesBackslash($except, $src, $offset)
	{
		$this->assertEquals($except, TextParser::indexOfQuotes(TextParser::QUOTE, $src, $offset));
	}



	function dataFindQuotesBackslash()
	{
		return [
			[-1, 'abcdefghijklm', 0], // nic
			[ 3, 'abc"def"ghi"jklm', 0], // více možností
			[ 8, 'abc\"def"ghi"jklm', 0], // vynechání escapované
			[ 8, 'abc\"def"ghi"jklm', 8], // escapovanou uvozovku přeskočím offsetem
			[12, 'abc\"def"ghi"jklm', 9], // první dvě uvozvoky přeskočím offsetem
			[ 0, '"abcdefghi"jklm', 0], // uplně na začátku, bejvá zrada
			[11, '\"abcdefghi"jklm', 0], // uplně na začátku
			[ 5, 'abc' . TextParser::BACKSLASH . TextParser::BACKSLASH . '"def"ghi"jklm', 0], // escapování escapování ruší escapování
			[10, 'abc' . TextParser::BACKSLASH . TextParser::BACKSLASH . TextParser::BACKSLASH . '"def"ghi"jklm', 0], // escapování escapování escapování ruší rušení escapování
			[ 2, '\\\"abcdefghi"jklm', 0], // uplně na začátku
			[ 2, '\\\"abcdefghi"jklm', 1],
			[ 2, '\\\"abcdefghi"jklm', 2],
			[12, '\\\"abcdefghi"jklm', 3],
			[ 5, 'abcde""fghi"jklm', 0], // escapování zdvojením se ignoruje
			[ 6, 'abc\'de""f\'gh\'i"jklm', 0], // ostatní uvozovky ignoruje
		];
	}



	/**
	 * @dataProvider dataFindQuotesDouble
	 */
	function testIndexOfQuotesDouble($except, $src, $offset)
	{
		$this->assertEquals($except, TextParser::indexOfQuotes(TextParser::QUOTE, $src, $offset, TextParser::ESCAPE_STYLE_DOUBLE));
	}



	function dataFindQuotesDouble()
	{
		return [
			[-1, 'abcdefghijklm', 0], // nic
			[ 3, 'abc"def"ghi"jklm', 0], // více možností
			[ 8, 'abc""def"ghi"jklm', 0], // vynechání escapované
			[ 8, 'abc""def"ghi"jklm', 8],
			[12, 'abc""def"ghi"jklm', 9],
			[12, 'abc""def"ghi"jklm', 10],
			[ 0, '"abcdefghi"jklm', 0], // uplně na začátku
			[11, '""abcdefghi"jklm', 0], // uplně na začátku
			[ 5, 'abc"""def"ghi"jklm', 0], // escapování escapování ruší escapování
			[10, 'abc""""def"ghi"jklm', 0], // escapování escapování escapování ruší rušení escapování
			[ 2, '"""abcdefghi"jklm', 0], // uplně na začátku
			[ 2, '"""abcdefghi"jklm', 1],
			[ 2, '"""abcdefghi"jklm', 2],
			[12, '"""abcdefghi"jklm', 3],
			[ 6, 'abcde\"fghi"jklm', 0], // escapování lomítkem se ignoruje
			[14, 'abc\'de""f\'gh\'i"jklm', 0], // ostatní uvozovky ignoruje
		];
	}



	/**
	 * @dataProvider dataFindQuotesWithoutEscaping
	 */
	function _testFindQuotesWithoutEscaping($except, $src, $offset)
	{
		$this->assertEquals($except, TextParser::indexOfQuotes(TextParser::QUOTE, $src, $offset, TextParser::ESCAPE_STYLE_NONE));
	}



	function dataFindQuotesWithoutEscaping()
	{
		return [
			[false,     'abcdefghijklm', 0], // nic
			[['"',  3], 'abc"def"ghi"jklm', 0], // více možností
			[['"',  8], 'abc""def"ghi"jklm', 0], // vynechání escapované
			[['"',  8], 'abc""def"ghi"jklm', 8],
			[['"', 12], 'abc""def"ghi"jklm', 9],
			[['"', 12], 'abc""def"ghi"jklm', 10],
			[['"',  0], '"abcdefghi"jklm', 0], // uplně na začátku
			[['"', 11], '""abcdefghi"jklm', 0], // uplně na začátku
			[['"',  5], 'abc"""def"ghi"jklm', 0], // escapování escapování ruší escapování
			[['"', 10], 'abc""""def"ghi"jklm', 0], // escapování escapování escapování ruší rušení escapování
			[['"',  2], '"""abcdefghi"jklm', 0], // uplně na začátku
			[['"',  2], '"""abcdefghi"jklm', 1],
			[['"',  2], '"""abcdefghi"jklm', 2],
			[['"', 12], '"""abcdefghi"jklm', 3],
			[['"',  6], 'abcde\"fghi"jklm', 0], // escapování lomítkem se ignoruje
		];
	}



	/**
	 * @dataProvider dataIndexOfText
	 */
	function testIndexOfText($except, $src, $offset)
	{
		$this->assertEquals($except, TextParser::indexOfText($src, $offset));
	}



	function dataIndexOfText()
	{
		return [
			[false,     'abcdefghijklm', 0], // nic
			[['"',  3], 'abc"def"ghi"jklm', 0], // více možností
			[['"',  8], 'abc\"def"ghi"jklm', 0],
			[['"',  8], 'abc\"def"ghi"jklm', 8],
			[['"', 12], 'abc\"def"ghi"jklm', 9],
			[['"', 12], 'abc\"def"ghi"jklm', 10],
			[['"',  0], '"abcdefghi"jklm', 0], // uplně na začátku
			[['"', 11], '\"abcdefghi"jklm', 0], // uplně na začátku
			[['"',  8], 'abc\"def"ghi"jklm', 0],
			[['"',  5], 'abc\\\"def"ghi"jklm', 0],
			[['"',  2], '\\\\"abcdefghi"jklm', 0], // uplně na začátku
			[['"',  2], '\\\\"abcdefghi"jklm', 1],
			[['"',  2], '\\\\"abcdefghi"jklm', 2],
			[['"', 12], '\\\\"abcdefghi"jklm', 3],
			[["'",  3], 'abc\'def"ghi"jklm', 0],
			[['"',  8], 'abc\\\'def"ghi"jklm', 0],
		];
	}



	/**
	 * @dataProvider dataMark
	 */
	function testMark($except, $src)
	{
		$this->assertEquals($except, TextParser::mark($src));
		$this->assertEquals($src, implode(TextParser::mark($src)));
	}



	function dataMark()
	{
		return [
			[['abcdefghijklm'],
				'abcdefghijklm'], // nic
			[['abcde', '"fghijklm'],
				'abcde"fghijklm'],
			[['abcde', '"fg"', 'hijklm'],
				'abcde"fg"hijklm'],
			[['abc', '"def"', 'ghi', '"jklm'],
				'abc"def"ghi"jklm'],
			[['abc\"def', '"ghi"', 'jklm'],
				'abc\"def"ghi"jklm'],
			[['"abcdefghi"', 'jklm'],
				'"abcdefghi"jklm'], // uplně na začátku
			[['\"abcdefghi', '"jklm'],
				'\"abcdefghi"jklm'], // uplně na začátku
			[['abc\"def', '"ghi"', 'jklm'],
				'abc\"def"ghi"jklm'],
			[['abc\\\\', '"def"', 'ghi', '"jklm'],
				'abc\\\"def"ghi"jklm'],
			[['\\\\', '"abcdefghi"', 'jklm'],
				'\\\\"abcdefghi"jklm'], // uplně na začátku
			[['abc', "'def\"ghi\"jklm"],
				'abc\'def"ghi"jklm'],
			[['abc\\\'def', '"ghi"', 'jklm'],
				'abc\\\'def"ghi"jklm'],
		];
	}



	function testIsText()
	{
		$this->assertFalse(TextParser::isText('abc'));
		$this->assertTrue(TextParser::isText('"abc'));
		$this->assertTrue(TextParser::isText("'abc"));
	}

}
