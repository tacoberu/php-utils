<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Data;


/**
 * Representation Date and Time or only Date (time ignore).
 *
 * @author Martin Takáč
 * @credits David Grudl
 */
class DateTime extends \DateTime
{

	/** average year in seconds */
	const YEAR = 31557600;


	/**
	 * Factory throwed exception when error.
	 * @return DateTime
	 */
	static function createFromFormat($format, $string, $timezone = Null)
	{
		$r = parent::createFromFormat($format, $string);
		$state = DateTime::getLastErrors();
		if ((count($state['warnings']) + count($state['errors'])) == 0) {
			return $r;
		}

		throw new \InvalidArgumentException(
				trim(implode('; ',
						array_unique(array_merge(
								(array) $state['warnings'],
								(array) $state['errors']
								))), '.;,') . ': `' . $string . "'.", 1);
	}



	/**
	 * DateTime object factory.
	 * @param  string|int|\DateTime
	 * @return DateTime
	 */
	static function from($time)
	{
		if ($time instanceof \DateTime || $time instanceof \DateTimeInterface) {
			return new self($time->format('Y-m-d H:i:s'), $time->getTimezone());
		}
		elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				$time += time();
			}
			$tmp = new self('@' . $time);
			$tmp->setTimeZone(new \DateTimeZone(date_default_timezone_get()));
			return $tmp;
		}
		// textual or NULL
		else {
			return new self($time);
		}
	}

}
