<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 24.3.18 14:39
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender;

use Nette\Utils\Html;


/**
 * Class BootstrapUtils. Utils for this library.
 * @package JCode\BootstrapFormRender
 */
class BootstrapUtils
{
	/**
	 * Converts element classes to an array if needed
	 *
	 * @param Html $control
	 */
	public static function standardizeClass(Html $control): void
	{
		$class = $control->class;
		if (is_string($class)) {
			$control->class = explode(' ', $class);
		}
	}
}
