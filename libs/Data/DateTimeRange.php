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
 */
class DateTimeRange
{


	private $from, $to;


	function __construct(\DateTime $from, \DateTime $to)
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
