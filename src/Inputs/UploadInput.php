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


use JCode\BootstrapFormRender\BootstrapRenderer;
use JCode\BootstrapFormRender\Enums\RendererConfig;
use Nette\Forms\Controls\UploadControl;
use Nette\Utils\Html;


/**
 * Class UploadInput. Single or multi upload of files.
 * @package JCode\BootstrapFormRender\Inputs
 * @property string $buttonCaption the text on the left part of the button, NOT label.
 */
class UploadInput extends UploadControl implements IValidationInput
{
	/** @var string */
	private $buttonCaption;


	/**
	 * @return string
	 * @see UploadInput::$buttonCaption
	 */
	public function getButtonCaption(): string
	{
		return $this->buttonCaption;
	}


	/**
	 * the text on the left part of the button
	 * @param string $buttonCaption
	 * @return static
	 * @see UploadInput::$buttonCaption
	 */
	public function setButtonCaption(string $buttonCaption): self
	{
		$this->buttonCaption = $buttonCaption;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function getControl(): Html
	{
		$control = parent::getControl();
		$control->class = 'custom-file-input';

		$el = Html::el('div', ['class' => ['custom-file']]);
		$el->addHtml($control);
		$el->addHtml(
			Html::el('label', [
				'class' => ['custom-file-label'],
				'for' => $this->getHtmlId(),
			])->setText($this->buttonCaption)
		);

		return $el;
	}


	/**
	 * Modify control in such a way that it explicitly shows its validation state.
	 * Returns the modified element.
	 * @param Html $control
	 * @return Html
	 */
	public function showValidation(Html $control): Html
	{
		$input = $control->getChildren()[0];

		/** @var BootstrapRenderer $renderer */
		$renderer = $this->getForm()->getRenderer();

		$renderer->configElem(
			$this->hasErrors() ? RendererConfig::INPUT_INVALID : RendererConfig::INPUT_VALID,
			$input
		);

		return $control;
	}
}
