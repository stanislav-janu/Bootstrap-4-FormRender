<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 9.7.17
 * Time: 20:02
 * This file belongs to the project bootstrap-4-forms
 * https://github.com/czubehead/bootstrap-4-forms
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Inputs;

use JCode\BootstrapFormRender\Traits\BootstrapButtonTrait;
use Nette\Forms\Controls\SubmitButton;


/**
 * Class SubmitButtonInput. Form can be submitted with this.
 * @package JCode\BootstrapFormRender\Inputs
 */
class SubmitButtonInput extends SubmitButton
{
	use BootstrapButtonTrait;
}
