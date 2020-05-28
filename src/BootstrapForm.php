<?php
declare(strict_types=1);
/**
 * Created by PhpStorm
 * Author: czubehead (http://petrcech.eu)
 * Date: 19.11.16
 * Time: 14:37
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender;

use JCode\BootstrapFormRender\Traits\AddRowTrait;
use JCode\BootstrapFormRender\Traits\BootstrapContainerTrait;
use Nette\ComponentModel\IContainer;
use Nette\Forms\Form;
use Nette\Forms\IFormRenderer;
use Nette\InvalidArgumentException;
use Nette\Utils\Html;


/**
 * Class BootstrapForm
 * Form rendered using Bootstrap 4
 * @package JCode\BootstrapFormRender
 * @property bool $ajax
 * @property int  $renderMode
 * @property bool $showValidation     If valid fields should explicitly be green if valid
 * @property bool $autoShowValidation If true, valid inputs will be explicitly green on unsuccessful submit
 */
class BootstrapForm extends Form
{
	use BootstrapContainerTrait;
	use AddRowTrait;

	/** @var string Class to be added if this is ajax. Defaults to 'ajax' */
	public $ajaxClass = 'ajax';

	/** @var Html */
	protected $elementPrototype;

	/** @var bool */
	private $isAjax = true;

	/** @var bool */
	private $showValidation = false;

	/** @var bool */
	private $autoShowValidation = true;


	/**
	 * BootstrapForm constructor.
	 *
	 * @param int|IContainer|null $container
	 */
	public function __construct($container = null)
	{
		parent::__construct($container);
		$this->setRenderer(new BootstrapRenderer);

		$prototype = Html::el('form', [
			'action' => '',
			'method' => self::POST,
			'class' => [],
		]);
		$this->elementPrototype = $prototype;

		/**
		 * @param BootstrapForm $form
		 */
		$this->onError[] = function ($form) {
			$form->showValidation = $this->autoShowValidation;
		};
	}


	public function getElementPrototype(): Html
	{
		return $this->elementPrototype;
	}


	/**
	 * @return \JCode\BootstrapFormRender\BootstrapRenderer|\Nette\Forms\IFormRenderer
	 */
	public function getRenderer(): IFormRenderer
	{
		return parent::getRenderer();
	}


	/**
	 * @param IFormRenderer $renderer
	 *
	 * @return static
	 */
	public function setRenderer(IFormRenderer $renderer = null): self
	{
		if (!$renderer instanceof BootstrapRenderer) {
			throw new InvalidArgumentException('Must be a BootstrapRenderer');
		}
		parent::setRenderer($renderer);

		return $this;
	}


	/**
	 * @return int
	 */
	public function getRenderMode(): int
	{
		return $this->getRenderer()
			->getMode();
	}


	/**
	 * @return bool if form is ajax. True by default.
	 */
	public function isAjax(): bool
	{
		return $this->isAjax;
	}


	/**
	 * @return bool
	 */
	public function isAutoShowValidation(): bool
	{
		return $this->autoShowValidation;
	}


	/**
	 * @param bool $autoShowValidation
	 *
	 * @return static
	 */
	public function setAutoShowValidation(bool $autoShowValidation): self
	{
		$this->autoShowValidation = $autoShowValidation;

		return $this;
	}


	/**
	 * If valid fields should explicitly be green
	 * @return bool
	 */
	public function isShowValidation(): bool
	{
		return $this->showValidation;
	}


	/**
	 * If valid fields should explicitly be green
	 *
	 * @param bool $showValidation
	 *
	 * @return static
	 */
	public function setShowValidation(bool $showValidation): self
	{
		$this->showValidation = $showValidation;

		return $this;
	}


	/**
	 * @param bool $isAjax
	 *
	 * @return static
	 */
	public function setAjax(bool $isAjax = true): self
	{
		$this->isAjax = $isAjax;

		BootstrapUtils::standardizeClass($this->getElementPrototype());
		$prototypeClass = $this->getElementPrototype()->class;

		$present = in_array($this->ajaxClass, $prototypeClass, true);
		if ($present && !$isAjax) {
			// remove the class
			$prototypeClass = array_diff($prototypeClass, [$this->ajaxClass]);
		} elseif (!$present && $isAjax) {
			// add class
			$prototypeClass[] = $this->ajaxClass;
		}
		$this->getElementPrototype()->class = $prototypeClass;

		return $this;
	}


	/**
	 * @param int $renderMode
	 *
	 * @return static
	 */
	public function setRenderMode(int $renderMode): self
	{
		$this->getRenderer()
			->setMode($renderMode);

		return $this;
	}
}
