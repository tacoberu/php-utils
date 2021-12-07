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


	/**
	 * @dataProvider dataSplitQuotes
	 */
	function testSplitQuotes($src, $expected, $sep = ' ')
	{
		$this->assertEquals($expected, Tokenizer::split($sep, $src));
	}



	function dataSplitQuotes()
	{
		return [
			['abx cdx \"efx \\\'ghx aaa"chx ijk" lm ne "a jak se" máš',
				['abx', 'cdx', '\"efx', '\\\'ghx', 'aaa"chx ijk"', 'lm', 'ne', '"a jak se"', 'máš']],
			['abx cdx',
				['abx', 'cdx']],
			['abx  cdx',
				['abx', 'cdx']],
			['abx / cdx',
				['abx ', ' cdx'],
				'/'],
			['abxcdx',
				['ab', 'cd'],
				'x'],
		];
	}


	function testParseQuotes()
	{
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



	/*
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
	*/



	/*
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
	*/


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
