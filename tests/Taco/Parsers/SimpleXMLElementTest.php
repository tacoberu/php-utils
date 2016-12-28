<?php
/**
 * Copyright (c) since 2004 Martin Takáč (http://martin.takac.name)
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace Taco\Parsers;

require_once __dir__ . '/../../../vendor/autoload.php';


use PHPUnit_Framework_TestCase;
use Nette;


/**
 * @call phpunit SimpleXMLElementTest.php
 */
class SimpleXMLElementTest extends PHPUnit_Framework_TestCase
{


	function testFromString()
	{
		$xml = SimpleXMLElement::fromString('<foo />');
		$this->assertInstanceof(SimpleXMLElement::class, $xml);
		$this->assertEquals("<?xml version=\"1.0\"?>\n<foo/>\n", $xml->asXml());
		//~ $this->assertNull($xml->getFile());
	}



	function testFromStringWithFile()
	{
		$xml = SimpleXMLElement::fromString('<foo />', 'anything');
		$this->assertInstanceof(SimpleXMLElement::class, $xml);
		$this->assertEquals("<?xml version=\"1.0\"?>\n<foo/>\n", $xml->asXml());
		//~ $this->assertEquals('anything', $xml->getFile());
	}



	function testFromFileIsNotFound()
	{
		$file = __dir__ . '/data/none';
		$this->setExpectedException('InvalidArgumentException', "File `{$file}' is not found.");
		SimpleXMLElement::fromFile($file);
	}



	function testFromFileHasMistake()
	{
		$file = __dir__ . '/data/mistake.xml';
		$this->setExpectedException('RuntimeException');
		SimpleXMLElement::fromFile($file);
	}



	function testFromStringHasMistake()
	{
		$content = file_get_contents(__dir__ . '/data/mistake.xml');
		$this->setExpectedException('RuntimeException');
		SimpleXMLElement::fromString($content);
	}



	function testFromFile()
	{
		$xml = SimpleXMLElement::fromFile(__dir__ . '/data/correct.xml');
		$this->assertInstanceof(SimpleXMLElement::class, $xml);
		$this->assertEquals("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<root>\n\t<sample/>\n</root>\n", $xml->asXml());
		//~ $this->assertEquals(__dir__ . '/data/correct.xml', $xml->getFile());
	}

}
