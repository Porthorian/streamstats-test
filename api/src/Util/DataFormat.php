<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Util;

use Porthorian\PDOWrapper\DBWrapper;

class DataFormat
{
	public static function getCommaListFromArray(array $flat_array, bool $qstr = true) : string
	{
		if (empty($flat_array))
		{
			return '';
		}

		$string = '';
		foreach ($flat_array as $element)
		{
			$string .= ($qstr ? DBWrapper::qstr($element) : $element).', ';
		}

		return trim($string, ', ');
	}
}