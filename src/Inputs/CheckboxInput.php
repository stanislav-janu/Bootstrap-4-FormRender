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

use JCode\BootstrapFormRender\Traits\StandardValidationTrait;
use Nette;
use Nette\Forms\Controls\Checkbox;
use Nette\Utils\Html;


/**
 * Class CheckboxInput. Single checkbox.
 * @package JCode\BootstrapFormRender\Inputs
 */
class CheckboxInput extends Checkbox implements IValidationInput
{
	use StandardValidationTrait {
		// we only want to use it on a specific child
		showValidation as protected _rawShowValidation;
	}


	/**
	 * Generates a checkbox
	 * @return Html
	 */
	public function getControl(): Html
	{
		return self::makeCheckbox($this->getHtmlName(), $this->getHtmlId(), $this->translate($this->caption), $this->value, false, $this->required, $this->disabled, $this->getRules());
	}


	/**
	 * Makes a Bootstrap checkbox HTML
	 *
	 * @param string                 $name
	 * @param string                 $htmlId
	 * @param string|Html|null       $caption
	 * @param bool                   $checked
	 * @param bool|mixed             $value pass false to omit
	 * @param bool                   $required
	 * @param bool                   $disabled
	 * @param Nette\Forms\Rules|null $rules
	 *
	 * @return Html
	 */
	public static function makeCheckbox(string $name, string $htmlId, $caption = null, bool $checked = false, $value = false, bool $required = false, bool $disabled = false, ?Nette\Forms\Rules $rules = null): Html
	{
		$label = Html::el('div', ['class' => ['custom-control', 'custom-checkbox']]);
		$input = Html::el('input', [
			'type' => 'checkbox',
			'class' => ['custom-control-input'],
			'name' => $name,
			'disabled' => $disabled,
			'required' => $required,
			'checked' => $checked,
			'id' => $htmlId,
			'data-nette-rules' => $rules ? Nette\Forms\Helpers::exportRules($rules) : false,
		]);
		if ($value !== false) {
			$input->attrs += [
				'value' => $value,
			];
		}

		$_label = Html::el('label', [
			'class' => ['custom-control-label'],
			'for' => $htmlId,
		]);
		if ($caption instanceof Nette\Utils\IHtmlString) {
			$_label->setHtml($caption);
		} else {
			$_label->setText($caption);
		}
		$label->addHtml($input);
		$label->addHtml($_label);

		$line = Html::el('div');
		$line->addHtml($label);

		return $label;
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
		// add validation classes to the first child, which is <input>
		$control->getChildren()[0] = $this->_rawShowValidation($control->getChildren()[0]);

		return $control;
	}
}
