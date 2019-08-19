<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 11.2.18 22:13
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Inputs;

use Nette\Utils\Html;


/**
 * Classes implementing this interface can explicitly show their validation status.
 * Interface IValidationInput
 * @package JCode\BootstrapFormRender\Inputs
 */
interface IValidationInput
{
	/**
	 * Modify control in such a way that it explicitly shows its validation state.
	 * Returns the modified element.
	 *
	 * @param Html $control
	 *
	 * @return Html
	 */
	public function showValidation(Html $control): Html;
}
