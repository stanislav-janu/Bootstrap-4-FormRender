<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 20.5.18 17:12
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Grid;

use JCode\BootstrapFormRender\BootstrapRenderer;
use JCode\BootstrapFormRender\Enums\RendererConfig;
use JCode\BootstrapFormRender\Traits\BootstrapContainerTrait;
use LogicException;
use Nette\ComponentModel\IComponent;
use Nette\Forms\IControl;
use Nette\SmartObject;
use Nette\Utils\Html;


/**
 * Class BootstrapCell.
 * Represents a row-column pair = table cell in Bootstrap grid system. This is the part with col-*-* class.
 * Only one component can be present.
 * @package JCode\BootstrapFormRender\Grid
 * @property-read int  $numOfColumns     Number of Bootstrap columns to occupy
 * @property-read IControl $childControl|null     Nested child control if any
 * @property-read Html $elementPrototype the Html div that will be rendered. You may define additional
 *                properties.
 */
class BootstrapCell
{
	use SmartObject;
	use BootstrapContainerTrait;

	/**
	 * Only use 'col' class (auto stretch)
	 */
	public const COLUMNS_NONE = false;
	/**
	 * Use 'col-auto'
	 */
	public const COLUMNS_AUTO = null;

	/** @var int */
	private $numOfColumns;

	/** @var IControl|null */
	private $childControl;

	/** @var BootstrapRow */
	private $row;

	/** @var Html */
	private $elementPrototype;


	/**
	 * BootstrapRow constructor.
	 *
	 * @param BootstrapRow   $row          Row this is a child of
	 * @param int|null|false $numOfColumns Number of Bootstrap columns to occupy. You can use an integer or
	 *                                     BootstrapCell::COLUMNS_* constant (see their docs for more)
	 */
	public function __construct(BootstrapRow $row, $numOfColumns)
	{
		$this->numOfColumns = $numOfColumns;
		$this->row = $row;

		$this->elementPrototype = Html::el('div');
	}


	/**
	 * Gets the prototype of this cell so you can define additional attributes. Col-* class is added during
	 * rendering and is not present, so don't add it...
	 * @return Html
	 */
	public function getElementPrototype(): Html
	{
		return $this->elementPrototype;
	}


	/**
	 * @return int|false|null
	 * @see BootstrapCell::$numOfColumns
	 */
	public function getNumOfColumns()
	{
		return $this->numOfColumns;
	}


	/**
	 * Renders the cell into Html object
	 * @return Html
	 */
	public function render(): Html
	{
		$element = $this->elementPrototype;
		/** @var BootstrapRenderer $renderer */
		$renderer = $this->row->getParent()->form->renderer;

		$element = $renderer->configElem(RendererConfig::GRID_CELL, $element);
		$element->class[] = $this->createClass();

		if ($this->childControl) {
			$pairHtml = $renderer->renderPair($this->childControl);
			$element->addHtml($pairHtml);
		}

		return $element;
	}


	/**
	 * Delegate to underlying component.
	 *
	 * @param \Nette\ComponentModel\IComponent $component
	 * @param string|null                      $name
	 * @param string|null                      $insertBefore
	 */
	protected function addComponent(IComponent $component, ?string $name, ?string $insertBefore = null)
	{
		if ($this->childControl) {
			throw new LogicException('child control for this cell has already been set, you cannot add another one');
		}

		/** @noinspection PhpInternalEntityUsedInspection */
		$this->row->addComponent($component, $name, $insertBefore);
		$this->childControl = $component;
	}


	protected function getComponent(string $name, bool $throw = false): ?IComponent
	{
		return null;
	}


	/**
	 * Creates column class based on numOfColumns
	 * @return string
	 */
	protected function createClass(): string
	{
		$cols = $this->numOfColumns;
		if ($cols === self::COLUMNS_NONE) {
			return 'col';
		} elseif ($cols === self::COLUMNS_AUTO) {
			return 'col-auto';
		} else {
			// number
			if ($this->row->gridBreakPoint != null) {
				return 'col-' . $this->row->gridBreakPoint . '-' . $this->numOfColumns;
			} else {
				return 'col-' . $this->numOfColumns;
			}
		}
	}
}
