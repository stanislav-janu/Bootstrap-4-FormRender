<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 10.7.17
 * Time: 17:34
 * This file belongs to the project bootstrap-4-forms.code
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Enums;

use DateTime;


/**
 * @package JCode\BootstrapFormRender\Enums
 */
class DateTimeFormat
{
	/**
	 * Checks if give time string is indeed in the format specified.
	 * Some leading zeros check might be omitted.
	 *
	 * @param string $format
	 * @param string $timeString
	 *
	 * @return bool
	 */
	public static function validate(string $format, string $timeString): bool
	{
		$time = DateTime::createFromFormat($format, $timeString);

		return ($time !== false) && ($timeString === $time->format($format));
	}
}
