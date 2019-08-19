<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 11.2.18 22:14
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Traits;

use JCode\BootstrapFormRender\BootstrapRenderer;
use JCode\BootstrapFormRender\Enums\RendererConfig;
use Nette\Utils\Html;


/**
 * Trait StandardValidationTrait.
 * Standard way to implement control validation.
 * @package JCode\BootstrapFormRender\Traits
 */
trait StandardValidationTrait
{
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
		$form = $this->getForm();

		if ($form !== null) {
			$renderer = $form->getRenderer();

			if ($renderer instanceof BootstrapRenderer) {
				$control = $renderer->configElem($this->hasErrors() ? RendererConfig::INPUT_INVALID : RendererConfig::INPUT_VALID, $control);

				if ($control instanceof Html) {
					return $control;
				}
			}
		}
		return Html::el('p')->setText('Error StandardValidationTrait');
	}
}
