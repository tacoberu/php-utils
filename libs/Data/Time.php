<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;

use InvalidArgumentException;


class Time
{

	private $hours = 0;
	private $minutes = 0;
	private $seconds = 0;


	/**
	 * Factory throwed exception when error.
	 * @return Time
	 */
	static function createFromFormat($format, $string)
	{
		$parser = DateTime::createFromFormat($format, $string);
		list($hours, $minutes, $seconds) = explode(':', $parser->format('G:i:s'));
		return new self($hours, $minutes, $seconds);
	}



	/**
	 * @param int|string|null $hours
	 * @param int|string|null $minutes
	 * @param int|string|null $seconds
	 */
	function __construct($hours = Null, $minutes = Null, $seconds = Null)
	{
		if ($hours === Null) {
			$hours = date('H');
		}
		if ($minutes === Null) {
			$minutes = date('i');
		}
		if ($seconds === Null) {
			$seconds = date('s');
		}
		$hours = (int)$hours;
		$minutes = (int)$minutes;
		$seconds = (int)$seconds;

		if ($hours < 0 || $hours > 23) {
			throw new InvalidArgumentException("Hours `$hours' is invalid in range 0 - 23.");
		}
		if ($minutes < 0 || $minutes > 59) {
			throw new InvalidArgumentException("Minutes `$minutes' is invalid in range 0 - 59.");
		}
		if ($seconds < 0 || $seconds > 59) {
			throw new InvalidArgumentException("Seconds `$seconds' is invalid in range 0 - 59.");
		}
		$this->hours = $hours;
		$this->minutes = $minutes;
		$this->seconds = $seconds;
	}



	/**
	 * @param string $format "H:i:s"
	 * @return string
	 */
	function format($format)
	{
		return strtr($format, array(
				// Lowercase Ante meridiem and Post meridiem    am or pm
				'a' => $this->hours < 12 ? 'am' : 'pm',
				// Uppercase Ante meridiem and Post meridiem    AM or PM
				'A' => $this->hours < 12 ? 'AM' : 'PM',
				// Swatch Internet time 000 through 999
				//~ 'B' =>
				// 12-hour format of an hour without leading zeros  1 through 12
				'g' => $this->hours < 13 ? $this->hours : ($this->hours - 12),
				// 24-hour format of an hour without leading zeros  0 through 23
				'G' => $this->hours,
				// 12-hour format of an hour with leading zeros     01 through 12
				'h' => $this->hours < 10 ? '0' . $this->hours : $this->hours,
				// 24-hour format of an hour with leading zeros     00 through 23
				'H' => $this->hours < 10 ? '0' . $this->hours : $this->hours,
				// Minutes with leading zeros   00 to 59
				'i' => $this->minutes < 10 ? '0' . $this->minutes : $this->minutes,
				// Seconds, with leading zeros  00 through 59
				's' => $this->seconds < 10 ? '0' . $this->seconds : $this->seconds,
				// Microseconds (added in PHP 5.2.2). Note that date() will always generate 000000 since it takes an integer parameter, whereas DateTime::format() does support microseconds.}
				//~ 'u' =>
				));
	}
}
