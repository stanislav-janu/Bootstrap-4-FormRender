<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 9.7.17
 * Time: 20:11
 * This file belongs to the project bootstrap-4-forms
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Enums;

/**
 * Class RenderMode
 * Defines the mode BootstrapRenderer works in.
 * @package Nette\Forms\Rendering
 * @see     BootstrapRenderer
 */
class RenderMode
{
	/**
	 * Labels above controls
	 */
	public const VERTICAL_MODE = 0;

	/**
	 * Labels beside controls
	 */
	public const SIDE_BY_SIDE_MODE = 1;

	/**
	 * Everything is inline if possible
	 */
	public const INLINE = 2;
}
