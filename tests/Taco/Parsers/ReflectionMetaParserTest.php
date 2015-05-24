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
require_once __dir__ . '/../../../libs/Taco/Parsers/ReflectionMetaParser.php';


use PHPUnit_Framework_TestCase;
use Nette;


class TestEntry // extends Nette\Object
{
	public $title;
	/** @meta(label="Obsah") */
	public $content;
}


class Test2Entry // extends Nette\Object
{
	private $title;
	/** @meta(label="Obsah") */
	public $content;

	/**
	 * @meta(label="Název")
	 * @required
	 */
	function getTitle()
	{
		return $this->title;
	}

	/**
	 * @meta(label="Name with prefix")
	 * @required
	 */
	function getTitleWithPrefix()
	{
		return '##' . $this->title;
	}
}


/**
 * @call phpunit --bootstrap ../../../../../bootstrap.php ReflectionTest.php
 */
class ReflectionMetaParserTest extends PHPUnit_Framework_TestCase
{

	private $parser;


	function setUp()
	{
		$this->parser = new ReflectionMetaParser();
	}



	function testEntryValue()
	{
		$entry = new TestEntry();
		$res = $this->parser->parse($entry);
		$this->assertCount(2, $res);
		$state = (object) array(
				'name' => 'title',
				'label' => 'Title',
				'type' => 'text',
				'required' => False,
				);
		$this->assertState($state, $res['title']);
		$state = (object) array(
				'name' => 'content',
				'label' => 'Obsah',
				'type' => 'text',
				'required' => False,
				);
		$this->assertState($state, $res['content']);
	}



	function testEntryClassName()
	{
		$res = $this->parser->parse('Taco\Parsers\TestEntry');
		$this->assertCount(2, $res);
		$state = (object) array(
				'name' => 'title',
				'label' => 'Title',
				'type' => 'text',
				'required' => False,
				);
		$this->assertState($state, $res['title']);
		$state = (object) array(
				'name' => 'content',
				'label' => 'Obsah',
				'type' => 'text',
				'required' => False,
				);
		$this->assertState($state, $res['content']);
	}



	function testEntry2Value()
	{
		$entry = new Test2Entry();
		$res = $this->parser->parse($entry);
		$this->assertCount(3, $res);
		$state = (object) array(
				'name' => 'titleWithPrefix',
				'label' => 'Name with prefix',
				'type' => 'text',
				'required' => True,
				);
		$this->assertState($state, $res['titleWithPrefix']);
		$state = (object) array(
				'name' => 'title',
				'label' => 'Název',
				'type' => 'text',
				'required' => True,
				);
		$this->assertState($state, $res['title']);
		$state = (object) array(
				'name' => 'content',
				'label' => 'Obsah',
				'type' => 'text',
				'required' => False,
				);
		$this->assertState($state, $res['content']);
	}



	private function assertState($pattern, $node)
	{
		$this->assertEquals($pattern, $node);
	}

}
