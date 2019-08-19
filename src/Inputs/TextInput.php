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
use JCode\BootstrapFormRender\Traits\StandardValidationTrait;
use Nette\InvalidArgumentException;
use Nette\Utils\Html;


/**
 * Class TextInput
 * @property string $placeholder HTML placeholder
 * @property bool   $autocomplete
 * @package JCode\BootstrapFormRender\Inputs
 */
class TextInput extends \Nette\Forms\Controls\TextInput implements IValidationInput, IAutocompleteInput
{
	use StandardValidationTrait;

	/** @var string */
	private $placeholder;

	/** @var null|bool */
	private $autocomplete;


	/**
	 * @inheritdoc
	 */
	public function __construct(?string $label = null, ?int $maxLength = null)
	{
		parent::__construct($label, $maxLength);
		$this->setRequired(false);
	}


	/**
	 * Gets the state of autocomplete: true=on,false=off,null=omit attribute
	 * @return bool|null
	 */
	public function getAutocomplete(): ?bool
	{
		return $this->autocomplete;
	}


	/**
	 * Turns autocomplete on or off.
	 *
	 * @param bool|null $bool null to omit attribute (default)
	 *
	 * @return static
	 */
	public function setAutocomplete(?bool $bool): self
	{
		if (!in_array($bool, [true, false, null], true)) {
			throw new InvalidArgumentException('valid values are only true/false/null');
		}
		$this->autocomplete = $bool;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getControl(): Html
	{
		$control = parent::getControl();
		BootstrapUtils::standardizeClass($control);

		$control->class[] = 'form-control';
		if ($this->placeholder !== null || $this->placeholder !== '') {
			$control->setAttribute('placeholder', $this->placeholder);
		}
		if ($this->autocomplete !== null) {
			$control->setAttribute('autocomplete', $this->autocomplete ? 'on' : 'off');
		}

		return $control;
	}


	/**
	 * @return string
	 * @see TextInput::$placeholder
	 */
	public function getPlaceholder(): string
	{
		return $this->placeholder;
	}


	/**
	 * @param string $placeholder
	 *
	 * @return static
	 * @see TextInput::$placeholder
	 */
	public function setPlaceholder(string $placeholder): self
	{
		$this->placeholder = $placeholder;

		return $this;
	}
}
