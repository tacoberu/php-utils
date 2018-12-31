<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Parsers;

use PHPUnit_Framework_TestCase;
use Nette\Utils;


class TokenizerTest extends PHPUnit_Framework_TestCase
{

	function testParseQuotes() {
		$src = 'abx cdx \"efx \\\'ghx aaa"chx ijk" lm ne "a jak se" máš';
		$this->assertEquals([
			'abx', 'cdx', '\"efx', '\\\'ghx', 'aaa', '"chx ijk"', 'lm', 'ne', '"a jak se"', 'máš'
		], Tokenizer::parse($src));
	}



	function testParseWhiteChars()
	{
		$src = 'abx cdx';
		$this->assertEquals([
			'abx', 'cdx'
		], Tokenizer::parse($src));
	}



	function testParseManyWhiteChars()
	{
		$src = 'abx  cdx';
		$this->assertEquals([
			'abx', 'cdx'
		], Tokenizer::parse($src));
	}



	/**
	 * @dataProvider dataTokenize
	 */
	function testTokenize($except, $src)
	{
		$this->assertEquals($except, (new Tokenizer())->tokenize($src));
		$this->assertEquals($src, implode('', (new Tokenizer())->tokenize($src)));
	}



	function dataTokenize()
	{
		return [
			[['abcdefghijklm']
				, 'abcdefghijklm'],
			[[new Token('(', ['defghi'], ')')]
				, '(defghi)'],
			[['aábcč', new Token('(', ['dďeéěfghií'], ')'), ' jklmnňoópqrřsštťuvwxyýzž',]
				, 'aábcč(dďeéěfghií) jklmnňoópqrřsštťuvwxyýzž'],
			[[
				'aábcč',
				new Token('(', ['dďeéěfghií'], ')'),
				' jklm',
				new Token('(', ['nňo'], ')'),
				'ópqrřsštťuvwxyýzž'
				]
				, 'aábcč(dďeéěfghií) jklm(nňo)ópqrřsštťuvwxyýzž'],
			[['abcde\"fghijklm']
				, 'abcde\"fghijklm'],
			[[
				'aábcč',
				new Token('(', [
					'dďeéěfghií',
					new Token('(', ['jklmnň'], ')'),
					'oópqrřs',
					], ')'),
				'štťuvwxyýzž']
				, 'aábcč(dďeéěfghií(jklmnň)oópqrřs)štťuvwxyýzž'],
			[['abcde"fgh"ijklm']
				, 'abcde"fgh"ijklm'],
			[[
				'abc',
				new Token('(', ['de"fgh"ijk'], ')'),
				'lm']
				, 'abc(de"fgh"ijk)lm'],
			[['abc',
				new Token('(', ['de\"fgh\"ijk'], ')'),
				'lm']
				, 'abc(de\"fgh\"ijk)lm'],
			[['abc',
				new Token('(', [
					'de\"f',
					new Token('(', ['gh'], ')'),
					'"ij"k'], ')'),
				'lm',
				]
				, 'abc(de\"f(gh)"ij"k)lm'],
		];
	}



	function testTokenize1()
	{
		$src = 'abc (ab1 x bc2) def [i jkl] mn';
		$tokenizer = (new Tokenizer([['(', ')'], ['[', ']']]));
		$this->assertEquals([
			'abc ',
			new Token('(', ['ab1 x bc2'], ')'),
			' def ',
			new Token('[', ['i jkl'], ']'),
			' mn',
		], $tokenizer->tokenize($src));
		$this->assertEquals($src, implode('', $tokenizer->tokenize($src)));
	}



	function testTokenize2()
	{
		$src = 'abx (ab1 bc2) def (a58) kjl';
		$this->assertEquals([
			'abx ',
			new Token('(', ['ab1 bc2'], ')'),
			' def ',
			new Token('(', ['a58'], ')'),
			' kjl',
		], (new Tokenizer())->tokenize($src));
		$this->assertEquals($src, implode('', (new Tokenizer())->tokenize($src)));
	}



	function testTokenize3_fail()
	{
		$src = 'abx {if}ab1 bc2{/} def (a58) kjl';
		$tokenizer = new Tokenizer([
			['{if}', '{/}'],
			['(', ')'],
		]);

		$this->assertEquals([
			'abx ',
			new Token('{if}', ['ab1 bc2'], '{/}'),
			' def ',
			new Token('(', ['a58'], ')'),
			' kjl',
		], $tokenizer->tokenize($src));
		$this->assertEquals($src, implode('', $tokenizer->tokenize($src)));
	}



	function testTokenize4()
	{
		$src = 'abx {if}ab1 "ahoj {if}" bc2{/} def (a58) kjl';
		$tokenizer = new Tokenizer([
			['{if}', '{/}'],
			['(', ')'],
		]);
		$this->assertEquals([
			'abx ',
			new Token('{if}', ['ab1 "ahoj {if}" bc2'], '{/}'),
			' def ',
			new Token('(', ['a58'], ')'),
			' kjl',
		], $tokenizer->tokenize($src));
		$this->assertEquals($src, implode('', $tokenizer->tokenize($src)));
	}



	function _testTokenize5()
	{
		// Nefunguje dobře.
		$src = 'abx {if}ab1 "ahoj {if}"{if}a{/} bc2{/} def (a58) kjl';
		$tokenizer = new Tokenizer([
			['{if}', '{/}'],
			['(', ')'],
		]);
		$this->assertEquals([
			'abx ',
			new Token('{if}', ['ab1 "ahoj {if}"',
				new Token('{if}', ['a'], '{/}'),
				' bc2'], '{/}'),
			' def ',
			new Token('(', ['a58'], ')'),
			' kjl',
		], $tokenizer->tokenize($src));
		$this->assertEquals($src, implode('', $tokenizer->tokenize($src)));
	}



	/**
	 * rozdělit i whitechars?
	 */
	function testTokenize6()
	{
		$src = 'a != 5 AND (b == c OR d > 5) AND id IN (1, 2, 65)';
		$tokenizer = new Tokenizer([
			['(', ')'],
		]);
		$this->assertEquals([
			'a != 5 AND ',
			new Token('(', ['b == c OR d > 5'], ')'),
			' AND id IN ',
			new Token('(', ['1, 2, 65'], ')'),
		], $tokenizer->tokenize($src));
		$this->assertEquals($src, implode('', $tokenizer->tokenize($src)));
	}



	/**
	 * Ignoruje to parsování prvních závorek.
	 */
	function testTokenizeCode()
	{
		$src = '
fn foo(a, b)
{
	return a + b;
}

fn boo(a, b)
{
	fn sum(a, b)
	{
		return a + b;
	}

	return a + b + sum(1, 8);
}

';

		$tokenizer = new Tokenizer([
			//~ ['(', ')'],
			['{', '}'],
		]);
		$this->assertEquals([
			"\nfn foo(a, b)\n",
			new Token('{', ["\n\treturn a + b;\n"], '}'),
			"\n\nfn boo(a, b)\n",
			new Token('{', [
				"\n\tfn sum(a, b)\n\t",
				new Token('{', ["\n\t\treturn a + b;\n\t"], '}'),
				"\n\n\treturn a + b + sum(1, 8);\n",
				//~ new Token('(', ["1, 8"], ')'),
				//~ ";\n",
				//~ new Token('{', ["\n\t\treturn a + b;\n\n\treturn a + b + sum(1, 8);\n"], '}'),
			], '}'),
			"\n\n",
		], $tokenizer->tokenize($src));
		$this->assertEquals($src, implode('', $tokenizer->tokenize($src)));
	}



	function testTokenize8()
	{
		$src = 'abx {if a=1}ab1 bc2{/} def';
		$tokenizer = new Tokenizer([
			['{if', '{/}'],
		]);
		$this->assertEquals([
			'abx ', new Token('{if', [' a=1}ab1 bc2'], '{/}'), ' def'
		], $tokenizer->tokenize($src));
		$this->assertEquals($src, implode('', $tokenizer->tokenize($src)));
	}



	function testTokenize9()
	{
		$src = 'abc(a + b)';
		$tokenizer = new Tokenizer();
		$this->assertEquals([
			'abc',
			new Token('(', ['a + b'], ')'),
		], $tokenizer->tokenize($src));
		$this->assertEquals($src, implode('', $tokenizer->tokenize($src)));
	}



	function testTokenize10()
	{
		$src = 'abc(a + b(xb))';
		$tokenizer = new Tokenizer();
		$this->assertEquals([
			'abc',
			new Token('(', ['a + b',
				new Token('(', ['xb'], ')'),
			], ')'),
		], $tokenizer->tokenize($src));
		$this->assertEquals($src, implode('', $tokenizer->tokenize($src)));
	}



	function ___testTokenizeTagAsToken()
	{
		new Tokenizer([
			Tokenizer::simple('(', ')'),
			Tokenizer::tagAsToken('{if', '}', '{/}'),
		]);

		$src = 'abx {if a=1}ab1 bc2{/} def';
		$tokenizer = new Tokenizer([
			['{if', '}', '{/}'],
		]);
		$this->assertEquals([
			'abx ',
			new Token(new Token('{if', 'a=1', '}'), ['ab1 bc2'], '{/}'),
			' def'
		], $tokenizer->tokenize($src));
	}



	function ___testTokenizeManyEnd()
	{
		$src = 'abx {if a=1}ab1 bc2{/if} def';
		$tokenizer = new Tokenizer([
			['{if', '}', ['{/}', '{/if}']],
		]);
		$this->assertEquals([
			'abx ',
			new Token(new Token('{if', 'a=1', '}'), ['ab1 bc2'], '{/if}'),
			' def'
		], $tokenizer->tokenize($src));
	}



	function __testTokenizeWithBeforeEndDelimiter()
	{
		//~ $tokenizer = new Utils\Tokenizer(array(
			//~ T_DNUMBER => '\d+',
			//~ T_WHITESPACE => '\s+',
			//~ T_STRING => '\w+',
		//~ ));

		//~ $src = 'abx (ab1 bc2) def (a58) kjl';
		//~ dump($src);
		//~ dump($tokenizer->tokenize("say \n123"));

		// Tak zrovna tady máme token, který končí začátek následujícího.
		$input = "
			@author David 'Grudl
			@package Nette
		";
		$tokenizer = new Tokenizer([
			['@', Tokenizer::beforeEnd(['@', '*/'])], // token končí některými z těchto tokenů. Ale neslouží jako uzavýrací, nejsou součástí tokenu.
		]);
		$this->assertEquals([
			"\n\t\t\t",
			new Token('@', ["author David 'Grudl\n\t\t\t"], null),
			new Token('@', ["package Nette\n\t\t\t"], null),
		], $tokenizer->tokenize($src));

		$parser = new Parser();
		$annotations = $parser->parse($input);
		dump($annotations);
	}

}



class Author
{
	public $name;

	public function __construct($name)
	{
		$this->name = $name;
	}
}



class Package
{
	public $name;

	public function __construct($name)
	{
		$this->name = $name;
	}
}



class Parser
{
	const T_AT = 1;
	const T_WHITESPACE = 2;
	const T_STRING = 3;

	/** @var \Nette\Utils\Tokenizer */
	private $tokenizer;

	/** @var \Nette\Utils\TokenIterator */
	private $iterator;

	public function __construct()
	{
		$this->tokenizer = new Utils\Tokenizer(array(
			self::T_AT => '@',
			self::T_WHITESPACE => '\\s+',
			self::T_STRING => '\\w+',
		));
	}



	public function parse($input)
	{
		$this->iterator = new Utils\TokenIterator($this->tokenizer->tokenize($input));

		$result = array();
		while ($this->iterator->nextToken()) {
			if ($this->iterator->isCurrent(self::T_AT)) {
				$result[] = $this->parseAnnotation();
			}
		}

		return $result;
	}



	protected function parseAnnotation()
	{
		$name = __namespace__ . '\\' . ucfirst($this->iterator->joinUntil(self::T_WHITESPACE));
		$this->iterator->nextUntil(self::T_STRING);
		$content = $this->iterator->joinUntil(self::T_AT);

		return new $name(trim($content));
	}

}
