<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 20.5.18 17:01
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Grid;

use JCode\BootstrapFormRender\BootstrapRenderer;
use JCode\BootstrapFormRender\Enums\RendererConfig;
use JCode\BootstrapFormRender\Traits\FakeControlTrait;
use Nette\ComponentModel\IComponent;
use Nette\ComponentModel\IContainer;
use Nette\Forms\Container;
use Nette\Forms\IControl;
use Nette\InvalidArgumentException;
use Nette\SmartObject;
use Nette\Utils\Html;


/**
 * Class BootstrapRow.
 * Represents a row in Bootstrap grid system.
 * @package JCode\BootstrapFormRender\Grid
 * @property string               $gridBreakPoint   Bootstrap breakpoint - usually xs, sm, md, lg. sm by
 *           default. Use NULL for no breakpoint.
 * @property-read string[]        $ownedNames       list of names of components which were added to this row
 * @property-read BootstrapCell[] $cells            cells in this row
 * @property-read Html            $elementPrototype the Html div that will be rendered. You may define
 *                additional properties.
 * @property-read string          $name             name of component
 */
class BootstrapRow implements IComponent, IControl
{
	use SmartObject;
	use FakeControlTrait;

	/**
	 * Number of columns in Bootstrap grid. Default is 12, but it can be customized.
	 * @var int
	 */
	public $numOfColumns = 12;

	/**
	 * Global name counter
	 * @var int
	 */
	private static $uidCounter = 0;

	/** @var string $name */
	private $name;

	/**
	 * Number of columns used by added cells.
	 * @var int
	 */
	private $columnsOccupied = 0;

	/**
	 * Form or container this belong to
	 * @var Container
	 */
	private $container;

	/** @var string */
	private $gridBreakPoint = 'sm';

	/** @var string[] */
	private $ownedNames = [];

	/** @var BootstrapCell[] */
	private $cells = [];

	/** @var Html */
	private $elementPrototype;

	/** @var array */
	private $options = [];


	/**
	 * BootstrapRow constructor.
	 *
	 * @param Container   $container Form or container this belongs to. Components will be added to this
	 * @param string|null $name      Optional name of this row. If none is supplied, it is generated
	 *                               automatically.
	 */
	public function __construct(Container $container, string $name = null)
	{
		$this->container = $container;
		if (!$name) {
			$name = 'bootstrap_row_' . ++self::$uidCounter;
		}
		$this->name = $name;

		$this->elementPrototype = Html::el();
	}


	/**
	 * Adds a new cell to which a control can be added.
	 *
	 * @param int $numOfColumns Number of grid columns to use up
	 *
	 * @return BootstrapCell the cell added.
	 */
	public function addCell(int $numOfColumns = BootstrapCell::COLUMNS_NONE): BootstrapCell
	{
		if ($this->columnsOccupied + $numOfColumns > $this->numOfColumns) {
			throw new InvalidArgumentException('the given number of columns with combination of already used' . " columns exceeds column limit ({$this->numOfColumns})");
		}

		$cell = new BootstrapCell($this, $numOfColumns);
		$this->cells[] = $cell;

		return $cell;
	}


	/**
	 * Delegate to underlying container and remember it.
	 *
	 * @param IComponent  $component
	 * @param string      $name
	 * @param string|null $insertBefore
	 *
	 * @internal
	 */
	public function addComponent(IComponent $component, string $name, string $insertBefore = null): void
	{
		$this->container->addComponent($component, $name, $insertBefore);
		$this->ownedNames[] = $name;
	}


	/**
	 * @param string $name
	 * @param bool   $throw
	 *
	 * @return \Nette\ComponentModel\IComponent|null
	 */
	public function getComponent(string $name, bool $throw = true): ?IComponent
	{
		return null;
	}


	/**
	 * @return BootstrapCell[]
	 * @see BootstrapRow::$cells
	 */
	public function getCells(): array
	{
		return $this->cells;
	}


	/**
	 * The container without content
	 * @return Html
	 * @see BootstrapRow::$elementPrototype
	 */
	public function getElementPrototype(): Html
	{
		return $this->elementPrototype;
	}


	/**
	 * @return string
	 * @see BootstrapRow::$gridBreakPoint
	 */
	public function getGridBreakPoint(): string
	{
		return $this->gridBreakPoint;
	}


	/**
	 * Sets the xs, sm, md, lg part.
	 *
	 * @param string $gridBreakPoint . NULL for no breakpoint.
	 *
	 * @return BootstrapRow
	 * @see BootstrapRow::$gridBreakPoint
	 */
	public function setGridBreakPoint(string $gridBreakPoint): self
	{
		$this->gridBreakPoint = $gridBreakPoint;

		return $this;
	}


	/**
	 * Component name
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * Returns the container
	 * @return \Nette\ComponentModel\IContainer|null
	 */
	public function getParent(): ?IContainer
	{
		return $this->container;
	}


	/**
	 * Sets the container
	 *
	 * @param \Nette\ComponentModel\IContainer|null $parent
	 * @param string|null                           $name
	 */
	public function setParent(IContainer $parent = null, string $name = null): void
	{
		$this->container = $parent;
	}


	/**
	 * Gets previously set option
	 *
	 * @param string|null $option
	 * @param string|null $default
	 *
	 * @return string|bool|int|null
	 */
	public function getOption(?string $option, ?string $default = null)
	{
		return isset($this->options[$option]) ? $this->options[$option] : $default;
	}


	/**
	 * Renders the row into a Html object
	 * @return Html
	 */
	public function render(): Html
	{
		/** @var BootstrapRenderer $renderer */
		$renderer = $this->container->form->renderer;

		$element = $renderer->configElem(RendererConfig::GRID_ROW, $this->elementPrototype);
		foreach ($this->cells as $cell) {
			$cellHtml = $cell->render();
			$element->addHtml($cellHtml);
		}

		return $element;
	}


	/**
	 * Sets option
	 *
	 * @param string $option
	 * @param string|bool|int|null $value
	 *
	 * @internal
	 */
	public function setOption(string $option, $value): void
	{
		$this->options[$option] = $value;
	}
}
