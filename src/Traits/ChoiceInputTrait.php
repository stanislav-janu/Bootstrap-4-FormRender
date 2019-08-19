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

use Nette\Forms\Controls\ChoiceControl;
use Nette\InvalidArgumentException;
use Nette\Utils\Html;


/**
 * Trait ChoiceInputTrait.
 * Provides basic functionality for inputs where one of more than one predefined values are possible.
 * @package JCode\BootstrapFormRender\Traits
 */
trait ChoiceInputTrait
{
	/** @var array items as user entered them - may be nested, unlike items, which are always flat. */
	protected $rawItems;


	/**
	 * Processes an associative array in a way that it has no nesting. Keys for
	 * nested arrays are lost, but nested arrays are merged.
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public function flatAssocArray(array $array): array
	{
		$ret = [];
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$ret += $this->flatAssocArray($value);
			} else {
				$ret[$key] = $value;
			}
		}

		return $ret;
	}


	/**
	 * Makes array of &lt;option&gt;. Can handle associative arrays just fine. Checks for duplicate values.
	 *
	 * @param array    $items
	 * @param callable $optionArgs     takes ($value,$caption) and spits out an array of &lt;option&gt;
	 *                                 attributes
	 * @param array    $valuesRendered for internal use. Do not change.
	 *
	 * @return array
	 * @throws InvalidArgumentException when $items have multiple of the same values
	 */
	public function makeOptionList(array $items, callable $optionArgs, array &$valuesRendered = []): array
	{
		$ret = [];
		foreach ($items as $value => $caption) {
			if (is_int($value)) {
				$value = (string) $value;
			}

			if (is_array($caption)) {
				// subgroup
				$option = Html::el('optgroup', ['label' => $value]);

				// options within the group
				$nested = $this->makeOptionList($caption, $optionArgs, $valuesRendered);

				foreach ($nested as $item) {
					$option->addHtml($item);
				}
			} else {
				if (in_array($value, $valuesRendered, true)) {
					throw new InvalidArgumentException("Value '$value' is used multiple times.");
				}
				$valuesRendered[] = $value;

				// normal option
				$option = Html::el('option', array_merge(['value' => $value], $optionArgs($value, $caption)));
				$option->setText($caption);
			}
			$ret[] = $option;
		}

		return $ret;
	}


	/**
	 * @param array $items Items to set. Associative arrays are supported.
	 * @param bool  $useKeys
	 *
	 * @return static
	 */
	public function setItems(array $items, bool $useKeys = true): self
	{
		/** @var ChoiceControl $this */
		$this->rawItems = $items;

		$processed = $this->flatAssocArray($items);
		/** @noinspection PhpUndefinedMethodInspection */
		parent::setItems($processed, $useKeys);

		return $this;
	}


	/**
	 * Check if whole control is disabled.
	 * This is false if only a set of values is disabled
	 * @return bool
	 */
	protected function isControlDisabled(): bool
	{
		return $this->disabled;
	}


	/**
	 * Check if a specific value is disabled. If whole control is disabled, returns false.
	 *
	 * @param mixed $value mixed value to check for
	 *
	 * @return bool
	 */
	protected function isValueDisabled($value): bool
	{
		$disabled = $this->disabled;
		if (is_array($disabled)) {
			return isset($disabled[$value]) && $disabled[$value];
		} elseif (!is_bool($disabled)) {
			return $disabled == $value;
		}

		return false;
	}


	/**
	 * Self-explanatory
	 * @param mixed $value
	 *
	 * @return bool
	 */
	protected function isValueSelected($value): bool
	{
		$val = $this->getValue();
		if ($value === null) {
			return false;
		} elseif (is_array($val)) {
			return in_array($value, $val, true);
		}

		return $value === $val;
	}
}
