<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Utils\Formaters;

use InvalidArgumentException,
	DateTime;


/**
 * Format column with DateTime
 */
class DateTimeFormater implements Formater
{

	/** @var string */
	public $format = 'Y-m-d H:i:s';


	/** @var string */
	public $emptyFormat = '-';


	/** @var bool */
	public $monospace = False;


	function __construct($format = Null, $emptyFormat = '-', $monospace = False)
	{
		if ($format) {
			$this->format = $format;
		}
		$this->emptyFormat = $emptyFormat;
		$this->monospace = $monospace;
	}



	/**
	 * Konfigurace formáteru.
	 * @param array
	 */
	function setOptions(array $opts)
	{
		if (count($opts) > 0) {
			$this->format = (string)$opts[0];
		}
		if (count($opts) > 1) {
			$this->emptyFormat = (string)$opts[1];
		}
		if (count($opts) > 2) {
			$this->monospace = (boolean)$opts[2];
		}
	}



	/**
	 * Render cell
	 * @param mixed $record record
	 * @return string
	 */
	function format($val)
	{
		$val = self::tryParse($val);
		if ( ! empty($val) && ! $val instanceof DateTime) {
			throw new InvalidArgumentException("Argument must be type of DateTime.");
		}

		if ($val) {
			if ($this->monospace) {
				$format = strtr($this->format, array(
						'G' => '##G##',
						'g' => '##g##',
						'j' => '##j##',
						'n' => '##n##',
						));
				return preg_replace_callback('~##(\d{1,2})##~', function($x) {
					return ($x[1] > 9) ? $x[1] : " $x[1]";
				}, $val->format($format));
			}
			return $val->format($this->format);
		}
		else {
			return $this->emptyFormat;
		}
	}



	/**
	 * @return DateTime | Null
	 */
	private static function tryParse($val)
	{
		if (empty($val) || $val instanceof DateTime) {
			return $val;
		}
		try {
			return new DateTime($val);
		}
		catch (\Exception $e) {
			return $val;
		}
	}

}