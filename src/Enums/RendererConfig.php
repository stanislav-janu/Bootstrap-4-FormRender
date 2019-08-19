<?php
declare(strict_types=1);
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 11.2.18 14:22
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender\Enums;

use JCode\BootstrapFormRender\BootstrapRenderer;
use JCode\BootstrapFormRender\Grid\BootstrapCell;
use JCode\BootstrapFormRender\Grid\BootstrapRow;


/**
 * Class RendererConfig.
 * An enum of possible BootstrapRenderer configuration options.
 * @package JCode\BootstrapFormRender\Enums
 * @see     BootstrapRenderer
 */
class RendererConfig
{
	/**
	 * The form element
	 */
	public const FORM = 'form';

	/**
	 * Form group
	 */
	public const GROUP = 'group';

	/**
	 * Label of a group
	 */
	public const GROUP_LABEL = 'group-label';

	/**
	 * Bootstrap row
	 * @see BootstrapRow
	 */
	public const GRID_ROW = 'grid-row';

	/**
	 * @see BootstrapCell
	 */
	public const GRID_CELL = 'grid-cell';

	/**
	 * Errors belonging to the form rather than an individual control. This is a container.
	 */
	public const FORM_OWN_ERRORS = 'form-own-errors';

	/**
	 * Multiple of those will be inside formOwnErrors
	 */
	public const FORM_OWN_ERROR = 'form-own-error';

	public const PAIR = 'pair';
	public const LABEL = 'label';
	public const DESCRIPTION = 'description';

	/**
	 * form group parts which are not label - input, feedback, description
	 */
	public const NON_LABEL = 'non-label';

	/**
	 * Influences on control HTML. Applied after validation.
	 */
	public const INPUT = 'input';
	public const INPUT_VALID = 'input-valid';
	public const INPUT_INVALID = 'input-invalid';

	/*
	 * Element that is normally an inline element within bootstrap
	 */

	/**
	 * Text saying if field is valid or invalid
	 */
	public const FEEDBACK = 'feedback';

	/**
	 * Child of 'feedback'. Extra attributes for invalid feedback
	 */
	public const FEEDBACK_VALID = 'feedback-valid';

	/**
	 * Child of 'feedback'. Extra attributes for valid feedback
	 */
	public const FEEDBACK_INVALID = 'feedback-invalid';

	/**
	 * Element name
	 */
	public const ELEMENT_NAME = 'element';

	/**
	 * Container. Must contain 'element' key. May be recursive.
	 */
	public const CONTAINER = 'container';

	public const ATTRIBUTES = 'attributes';

	/**
	 * Class or array of classes to set
	 */
	public const CLASS_SET = 'class-set';

	/**
	 * Class or array of classes to add
	 */
	public const CLASS_ADD = 'class-add';

	/**
	 * Class or array to classes to remove if they exist
	 */
	public const CLASS_REMOVE = 'class-remove';
}
