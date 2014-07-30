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

namespace Taco\Data;



class DateTime extends \DateTime
{


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
	public static function from($time)
	{
		if ($time instanceof \DateTime || $time instanceof \DateTimeInterface) {
			return new static($time->format('Y-m-d H:i:s'), $time->getTimezone());
		}
		elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				$time += time();
			}
			$tmp = new static('@' . $time);
			$tmp->setTimeZone(new \DateTimeZone(date_default_timezone_get()));
			return $tmp;
		}
		// textual or NULL
		else {
			return new static($time);
		}
	}

}
