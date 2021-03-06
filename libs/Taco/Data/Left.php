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



/**
 * The Either type represents values with two possibilities: a value of type
 * Either a b is either Left a or Right b.
 *
 * The Either type is sometimes used to represent a value which is either
 * correct or an error; by convention, the Left constructor is used to
 * hold an error value and the Right constructor is used to hold a
 * correct value (mnemonic: "right" also means "correct").
 *
 * @inspired by Haskell Data-Either
 */
class Left implements Either
{

	private $msg;


	private $code;


	/**
	 * @param int
	 * @param string
	 */
	public function __construct($msg, $code = 0)
	{
		$this->msg = $msg;
		$this->code = $code;
	}



	/**
	 * Make instance of Left from Exception;
	 */
	public static function fromException(\Exception $e)
	{
		return new self($e->getMessage(), $e->getCode());
	}



	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->msg;
	}



	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}



}
