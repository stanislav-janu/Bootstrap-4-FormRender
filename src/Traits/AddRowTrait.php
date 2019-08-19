<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 28.5.18 20:51
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Traits;

use JCode\BootstrapFormRender\Grid\BootstrapRow;


/**
 * Trait AddRowTrait. Implements method to add a bootstrap row.
 * @package JCode\BootstrapFormRender\Traits
 */
trait AddRowTrait
{
	/**
	 * Adds a new Grid system row.
	 *
	 * @param string|null $name optional. If null is passed, it is generated.
	 *
	 * @return BootstrapRow
	 */
	public function addRow(?string $name = null): BootstrapRow
	{
		/** @noinspection PhpParamsInspection */
		$row = new BootstrapRow($this, $name);
		$this->addComponent($row, $row->name);

		return $row;
	}
}
