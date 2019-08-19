<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 11.2.18 23:12
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Inputs;

/**
 * Interface IAutocompleteInput.
 * Inputs which have toggleable autocomplete.
 * @package JCode\BootstrapFormRender\Inputs
 */
interface IAutocompleteInput
{
	/**
	 * Gets the state of autocomplete: true=on,false=off,null=omit attribute
	 * @return bool|null
	 */
	public function getAutocomplete(): ?bool;

	/**
	 * Turns autocomplete on or off.
	 * @param bool|null $bool null to omit attribute (default)
	 * @return static
	 */
	public function setAutocomplete(?bool $bool);
}
