<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 20.5.18 19:36
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Traits;

use Nette\NotImplementedException;


/**
 * Trait FakeControlTrait.
 * Implements absolute minimum of functionality to be used as a control
 * @package JCode\BootstrapFormRender\Traits
 */
trait FakeControlTrait
{
	/**
	 * Always returns an empty array
	 * @internal
	 */
	public function getErrors(): array
	{
		return [];
	}


	/**
	 * Not supported
	 * @internal
	 */
	public function getValue(): ?bool
	{
		return null;
	}


	public function isDisabled(): bool
	{
		return true;
	}


	/**
	 * Is control value excluded from $form->getValues() result?
	 * @return true
	 */
	public function isOmitted(): bool
	{
		return true;
	}


	/**
	 * Not supported
	 *
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		throw new NotImplementedException;
	}


	/**
	 * Do nothing
	 * @internal
	 */
	public function validate(): void
	{
	}
}
