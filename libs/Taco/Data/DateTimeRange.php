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


use DateTime;


/**
 * Representation Date and Time or only Date (time ignore).
 *
 * @author Martin Takáč
 */
class DateTimeRange
{


	private $from, $to;


	function __construct(DateTime $from, DateTime $to)
	{
		$this->from = $from;
		$this->to = $to;
	}



	/**
	 * @return DateTime
	 */
	function getFrom()
	{
		return $this->from;
	}



	/**
	 * @return DateTime
	 */
	function getTo()
	{
		return $this->to;
	}

}
