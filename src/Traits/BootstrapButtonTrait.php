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

use JCode\BootstrapFormRender\BootstrapUtils;
use Nette\Utils\Html;


/**
 * Trait BootstrapButtonTrait. Modifies an existing button class such that it returns a bootstrap button.
 * @package JCode\BootstrapFormRender
 * @property string $btnType
 */
trait BootstrapButtonTrait
{
	/** @var string */
	private $btnClass = 'btn-primary';


	/**
	 * Gets additional button class. Default is btn-primary.
	 * @return string
	 */
	public function getBtnClass(): string
	{
		return $this->btnClass;
	}


	/**
	 * Sets additional button class. Default is btn-primary
	 *
	 * @param string $btnClass
	 *
	 * @return static
	 */
	public function setBtnClass(string $btnClass): self
	{
		$this->btnClass = $btnClass;

		return $this;
	}


	/**
	 * @param string|null $caption
	 *
	 * @return \Nette\Utils\Html
	 */
	public function getControl($caption = null): Html
	{
		$control = parent::getControl($caption);
		$this->addBtnClass($control);

		return $control;
	}


	/**
	 * @param Html $element
	 */
	protected function addBtnClass(Html $element): void
	{
		BootstrapUtils::standardizeClass($element);
		$element->class[] = 'btn ' . $this->getBtnClass();
	}
}
