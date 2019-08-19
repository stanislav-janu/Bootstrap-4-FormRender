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
use Nette\Forms\Controls\Button;
use Nette\Utils\Html;


/**
 * Class ButtonInput.
 * Returns &lt;button&gt; whose content can be set as caption. This is not a submit button.
 * @package JCode\BootstrapFormRender
 * @property string $btnClass
 */
class ButtonInput extends Button
{
	use BootstrapButtonTrait;

	/**
	 * ButtonInput constructor.
	 *
	 * @param null|string|Html $content
	 */
	public function __construct($content = null)
	{
		parent::__construct($content);
	}


	/**
	 * Control HTML
	 *
	 * @param null|string|Html $content
	 *
	 * @return Html
	 */
	public function getControl($content = null): Html
	{
		$btn = Html::el('button', [
			'type' => 'button',
			'name' => $this->getHtmlName(),
		]);
		$btn->setHtml($content === null ? $this->caption : $content);
		$this->addBtnClass($btn);

		return $btn;
	}
}
