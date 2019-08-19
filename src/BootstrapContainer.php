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

namespace JCode\BootstrapFormRender;

use JCode\BootstrapFormRender\Traits\AddRowTrait;
use JCode\BootstrapFormRender\Traits\BootstrapContainerTrait;
use Nette\Forms\Container;


/**
 * Class BootstrapContainer.
 * Container that has all the bootstrap add* methods.
 * @package JCode\BootstrapFormRender
 */
class BootstrapContainer extends Container
{
	use BootstrapContainerTrait;
	use AddRowTrait;
}
