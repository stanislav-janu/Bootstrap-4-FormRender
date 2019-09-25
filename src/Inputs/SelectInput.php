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

use JCode\BootstrapFormRender\BootstrapUtils;
use JCode\BootstrapFormRender\Traits\ChoiceInputTrait;
use JCode\BootstrapFormRender\Traits\StandardValidationTrait;
use Nette;
use Nette\Forms\Controls\SelectBox;


/**
 * Class SelectInput.
 * Single select.
 * @package JCode\BootstrapFormRender
 */
class SelectInput extends SelectBox implements IValidationInput
{
	use ChoiceInputTrait;
	use StandardValidationTrait;

	/**
	 * Generates control's HTML element.
	 */
	public function getControl(): Nette\Utils\Html
	{
		$control = parent::getControl();
		BootstrapUtils::standardizeClass($control);
		$control->class[] = 'form-control';
		return $control;
	}
}
