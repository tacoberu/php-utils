<?php
/**
 * Copyright (c) since 2004 Martin Takáč (http://martin.takac.name)
 * @license   https://opensource.org/licenses/MIT MIT
 */

namespace Taco\Parsers;

use RuntimeException,
	InvalidArgumentException;


/**
 * Source as XML content.
 * @author Martin Takáč <martin@takac.name>
 */
class SimpleXMLElement extends \SimpleXMLElement
{

	/**
	 * Name of original file source.
	 */
	//~ private $__file = 'aaa';


	/**
	 * @return SimpleXMLElement
	 */
	static function fromFile($file)
	{
		if ( ! file_exists($file)) {
			throw new InvalidArgumentException("File `{$file}' is not found.");
		}

		libxml_use_internal_errors(True);
		$xml = simplexml_load_file($file, __class__);

		if (empty($xml)) {
			$errors = libxml_get_errors();
			if ($errors) {
				$result = array();
				$xmllines = explode("\n", file_get_contents($file));
				foreach ($errors as $error) {
					$result[] = self::formatXmlError($error, $xmllines);
				}
				libxml_clear_errors();

				throw new RuntimeException("Invalid format data\n" . implode(PHP_EOL, $result));
			}
		}

		//~ $xml->__file = $file; // @TODO Why?
		return $xml;
	}



	/**
	 * @param string $data As `<foo><boo>ahoj</boo></foo>`
	 * @return SimpleXMLElement
	 */
	static function fromString($data, $file = Null)
	{
		libxml_use_internal_errors(True);
		$xml = simplexml_load_string($data, __class__);

		if (empty($xml)) {
			$errors = libxml_get_errors();
			if ($errors) {
				$result = array();
				foreach ($errors as $error) {
					$result[] = self::formatXmlError($error, []);
				}
				libxml_clear_errors();

				throw new RuntimeException("Invalid format data\n" . implode(PHP_EOL, $result));
			}
		}

		//~ $xml->__file = $file;
		return $xml;
	}



	/**
	 * @return string
	 * /
	function getFile()
	{
		return $this->__file;
	}*/



	/**
	 *	Naformátuje chyby včetně zdrojového kodu.
	 */
	private static function formatXmlError(\LibXMLError $error, array $xml)
	{
		$return = '';
		if (count($xml) > 1) {
			$return .= strtr($xml[$error->line - 1], "\t", " ") . "\n";
			$return .= str_repeat('-', $error->column) . "^\n";
		}

		switch ($error->level) {
			case LIBXML_ERR_WARNING:
				$return .= "Warning $error->code: ";
				break;
			 case LIBXML_ERR_ERROR:
				$return .= "Error $error->code: ";
				break;
			case LIBXML_ERR_FATAL:
				$return .= "Fatal Error $error->code: ";
				break;
		}

		$return .= trim($error->message) .
				   "\n  Line: $error->line" .
				   "\n  Column: $error->column";

		if ($error->file) {
			$return .= "\n  File: $error->file";
		}

		return "$return\n\n--------------------------------------------\n\n";
	}

}
