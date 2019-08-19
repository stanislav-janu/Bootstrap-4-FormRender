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

use JCode\BootstrapFormRender\Traits\ChoiceInputTrait;
use JCode\BootstrapFormRender\Traits\StandardValidationTrait;
use Nette\Forms\Controls\CheckboxList;
use Nette\Utils\Html;


/**
 * Class CheckboxListInput.
 * Multiple checkboxes in a list.
 * @package JCode\BootstrapFormRender\Inputs
 */
class CheckboxListInput extends CheckboxList implements IValidationInput
{
	use ChoiceInputTrait;
	use StandardValidationTrait {
		showValidation as protected _rawShowValidation;
	}


	/**
	 * @inheritdoc
	 */
	public function getControl(): Html
	{
		parent::getControl();
		$fieldset = Html::el('fieldset', [
			'disabled' => $this->isControlDisabled(),
		]);

		$baseId = $this->getHtmlId();
		$c = 0;
		foreach ($this->items as $value => $caption) {
			$line = CheckboxInput::makeCheckbox($this->getHtmlName(), $baseId . $c, $caption, $this->isValueSelected($value), $value, false, $this->isValueDisabled($value), $this->getRules());

			$fieldset->addHtml($line);
			$c++;
		}

		return $fieldset;
	}


	/**
	 * Modify control in such a way that it explicitly shows its validation state.
	 * Returns the modified element.
	 *
	 * @param Html $control
	 *
	 * @return Html
	 */
	public function showValidation(Html $control): Html
	{
		// same parent, but no children
		$fieldset = Html::el($control->getName(), $control->attrs);
		/** @var Html $label */
		foreach ($control->getChildren() as $label) {
			$input = $label->getChildren()[0];
			$label->getChildren()[0] = $this->_rawShowValidation($input);
			$fieldset->addHtml($label);
		}

		return $fieldset;
	}
}
