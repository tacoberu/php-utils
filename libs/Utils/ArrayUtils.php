<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Utils;


class ArrayUtils
{

	/**
	 * @param array<mixed> $xs
	 * @return array<array<mixed>>
	 */
	static function cartesianProduct(array $xs)
	{
		$ret = array();
		for ($i = 0; $i < count($xs); $i++) {
			$tail = $xs;
			$x = array_splice($tail, $i, 1); // removes and returns the i'th element
			if (count($tail) > 1) {
				foreach (self::cartesianProduct($tail) as $ends) {
					$ret[] = array_merge($x, $ends);
				}
			}
			else {
				$ret[] = array_merge($x, $tail);
			}
		}
		return $ret;
	}


}
