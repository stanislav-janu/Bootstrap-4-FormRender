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

namespace JCode\BootstrapFormRender\Traits;

use JCode\BootstrapFormRender\BootstrapContainer;
use JCode\BootstrapFormRender\Inputs\ButtonInput;
use JCode\BootstrapFormRender\Inputs\CheckboxInput;
use JCode\BootstrapFormRender\Inputs\CheckboxListInput;
use JCode\BootstrapFormRender\Inputs\DateTimeInput;
use JCode\BootstrapFormRender\Inputs\MultiSelectInput;
use JCode\BootstrapFormRender\Inputs\RadioInput;
use JCode\BootstrapFormRender\Inputs\SelectInput;
use JCode\BootstrapFormRender\Inputs\SubmitButtonInput;
use JCode\BootstrapFormRender\Inputs\TextAreaInput;
use JCode\BootstrapFormRender\Inputs\TextInput;
use Nette\ComponentModel\IComponent;
use Nette\Forms\Container;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\CheckboxList;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\SubmitButton;
use Nette\Forms\Controls\TextArea;
use Nette\Forms\Controls\UploadControl;
use Nette\Forms\Form;
use Nette\Utils\Html;


/**
 * Trait BootstrapContainerTrait.
 * Implements methods to add inputs.
 * @package JCode\BootstrapFormRender
 */
trait BootstrapContainerTrait
{
	/**
	 * @param string           $name
	 * @param null|string|Html $content
	 * @param string           $btnClass secondary button class (primary is 'btn')
	 *
	 * @return ButtonInput
	 */
	public function addButton(string $name, $content = null, string $btnClass = 'btn-secondary'): Button
	{
		$comp = new ButtonInput($content);
		$comp->setBtnClass($btnClass);
		$this->addComponent($comp, $name);

		return $comp;
	}


	/**
	 * @param string             $name
	 * @param string|object|null $caption
	 *
	 * @return \Nette\Forms\Controls\Checkbox
	 */
	public function addCheckbox(string $name, $caption = null): Checkbox
	{
		$comp = new CheckboxInput($caption);
		$this->addComponent($comp, $name);

		return $comp;
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 * @param array|null         $items
	 *
	 * @return \Nette\Forms\Controls\CheckboxList
	 */
	public function addCheckboxList(string $name, $label = null, array $items = null): CheckboxList
	{
		$comp = new CheckboxListInput($label, $items);
		$this->addComponent($comp, $name);

		return $comp;
	}


	/**
	 * @param \Nette\ComponentModel\IComponent $component
	 * @param string|null                      $name
	 * @param string|null                      $insertBefore
	 *
	 * @return static
	 */
	abstract public function addComponent(IComponent $component, ?string $name, ?string $insertBefore = null);


	/**
	 * @param string $name
	 *
	 * @return BootstrapContainer
	 */
	public function addContainer($name): Container
	{
		$control = new BootstrapContainer;
		if (property_exists($this, 'currentGroup')) {
			$control->setCurrentGroup($this->currentGroup);
			if ($this->currentGroup !== null) {
				/** @noinspection PhpUndefinedMethodInspection */
				$this->currentGroup->add($control);
			}
		}

		$this->addComponent($control, $name);

		return $control;
	}


	/**
	 * Adds a datetime input.
	 *
	 * @param string             $name
	 * @param string|object|null $label
	 *
	 * @return DateTimeInput
	 */
	public function addDateTime(string $name, $label = null): DateTimeInput
	{
		$comp = new DateTimeInput($label);
		$this->addComponent($comp, $name);

		return $comp;
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 *
	 * @return \Nette\Forms\Controls\TextInput
	 */
	public function addEmail(string $name, $label = null): \Nette\Forms\Controls\TextInput
	{
		return $this->addText($name, $label)
			->addRule(Form::EMAIL);
	}


	/**
	 * Adds error to a specific component
	 *
	 * @param string $componentName
	 * @param string $message
	 */
	public function addInputError(string $componentName, string $message): void
	{
		$component = $this->getComponent($componentName);

		if ($component !== null && method_exists($component, 'addError')) {
			$component->addError($message);
		}
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 *
	 * @return \Nette\Forms\Controls\TextInput
	 */
	public function addInteger(string $name, $label = null): \Nette\Forms\Controls\TextInput
	{
		return $this->addText($name, $label)
			->addRule(Form::INTEGER);
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 * @param array|null         $items
	 * @param int|null           $size
	 *
	 * @return \Nette\Forms\Controls\MultiSelectBox
	 */
	public function addMultiSelect(string $name, $label = null, array $items = null, int $size = null): MultiSelectBox
	{
		$comp = new MultiSelectInput($label, $items);
		if ($size !== null) {
			$comp->setHtmlAttribute('size', $size);
		}
		$this->addComponent($comp, $name);

		return $comp;
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 *
	 * @return \Nette\Forms\Controls\UploadControl
	 */
	public function addMultiUpload(string $name, $label = null): UploadControl
	{
		return $this->addUpload($name, $label, true);
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 * @param int|null           $cols
	 * @param int|null           $maxLength
	 *
	 * @return \Nette\Forms\Controls\TextInput
	 */
	public function addPassword(string $name, $label = null, int $cols = null, int $maxLength = null): \Nette\Forms\Controls\TextInput
	{
		return $this->addText($name, $label, $cols, $maxLength)
			->setHtmlType('password');
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 * @param array|null         $items
	 *
	 * @return \Nette\Forms\Controls\RadioList
	 */
	public function addRadioList(string $name, $label = null, array $items = null): RadioList
	{
		$comp = new RadioInput($label, $items);
		$this->addComponent($comp, $name);

		return $comp;
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 * @param array|null         $items
	 * @param int|null           $size
	 *
	 * @return \Nette\Forms\Controls\SelectBox
	 */
	public function addSelect(string $name, $label = null, array $items = null, int $size = null): SelectBox
	{
		$comp = new SelectInput($label, $items);
		if ($size !== null) {
			$comp->setHtmlAttribute('size', $size);
		}
		$this->addComponent($comp, $name);

		return $comp;
	}


	/**
	 * @param string             $name
	 * @param string|object|null $caption
	 * @param string             $btnClass
	 *
	 * @return \Nette\Forms\Controls\SubmitButton
	 */
	public function addSubmit(string $name, $caption = null, string $btnClass = 'btn-primary'): SubmitButton
	{
		$comp = new SubmitButtonInput($caption);
		$comp->setBtnClass($btnClass);
		$this->addComponent($comp, $name);

		return $comp;
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 * @param int|null           $cols
	 * @param int|null           $maxLength
	 *
	 * @return \Nette\Forms\Controls\TextInput
	 */
	public function addText(string $name, $label = null, int $cols = null, int $maxLength = null): \Nette\Forms\Controls\TextInput
	{
		$comp = new TextInput($label);
		if ($cols !== null) {
			$comp->setHtmlAttribute('cols', $cols);
		}
		if ($maxLength != null) {
			$comp->setHtmlAttribute('maxlength', $cols);
		}
		$this->addComponent($comp, $name);

		return $comp;
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 * @param int|null           $cols
	 * @param int|null           $rows
	 *
	 * @return \Nette\Forms\Controls\TextArea
	 */
	public function addTextArea(string $name, $label = null, int $cols = null, int $rows = null): TextArea
	{
		$comp = new TextAreaInput($label);
		if ($cols !== null) {
			$comp->setHtmlAttribute('cols', $cols);
		}
		if ($rows !== null) {
			$comp->setHtmlAttribute('rows', $rows);
		}

		$this->addComponent($comp, $name);

		return $comp;
	}


	/**
	 * @param string             $name
	 * @param string|object|null $label
	 * @param bool               $multiple
	 *
	 * @return \Nette\Forms\Controls\UploadControl
	 */
	public function addUpload(string $name, $label = null, bool $multiple = false): UploadControl
	{
		$comp = new UploadControl($label, $multiple);
		$this->addComponent($comp, $name);

		return $comp;
	}
}
