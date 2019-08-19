<?php
declare(strict_types=1);

/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 9.7.17
 * Time: 20:02
 * This file belongs to the project bootstrap-4-forms
 * https://github.com/czubehead/bootstrap-4-forms
 * based on the original FormRenderer by David Grudl
 * Updated 19. 8. 2019 by Stanislav Janů (https://www.lweb.cz)
 */

namespace JCode\BootstrapFormRender;

use JCode\BootstrapFormRender\Enums\RendererConfig as Cnf;
use JCode\BootstrapFormRender\Enums\RendererOptions;
use JCode\BootstrapFormRender\Enums\RenderMode;
use JCode\BootstrapFormRender\Grid\BootstrapRow;
use JCode\BootstrapFormRender\Inputs\IValidationInput;
use Nette;
use Nette\Utils\Html;
use function Safe\parse_url;
use function Safe\preg_split;


/**
 * Converts a Form into Bootstrap 4 HTML output.
 * @property int        $mode
 * @property string     $gridBreakPoint    Bootstrap grid breakpoint for side-by-side view. Default is 'sm'.
 *           NULL means not to use a breakpoint
 * @property-read array $config
 * @property-read array $configOverride
 * @property bool       $groupHidden       if true, hidden fields will be grouped at the end. If false,
 *           hidden fields are placed where they were added. Default is true.
 */
class BootstrapRenderer implements Nette\Forms\IFormRenderer
{
	use Nette\SmartObject;

	public const DEFAULT_LABEL_COLUMNS = 3;
	public const DEFAULT_CONTROL_COLUMNS = 9;

	/**
	 * Bootstrap grid breakpoint for side-by-side view
	 * @var string
	 */
	protected $gridBreakPoint = 'sm';

	/** @var BootstrapForm */
	protected $form;

	/** @var int */
	protected $labelColumns = self::DEFAULT_LABEL_COLUMNS;

	/** @var int */
	protected $controlColumns = self::DEFAULT_CONTROL_COLUMNS;

	/** @var int current render mode */
	private $renderMode = RenderMode::SIDE_BY_SIDE_MODE;

	/** @var bool */
	private $groupHidden = true;


	/**
	 * BootstrapRenderer constructor.
	 *
	 * @param int $mode
	 */
	public function __construct($mode = RenderMode::VERTICAL_MODE)
	{
		$this->setMode($mode);
	}


	/**
	 * Sets the form for which to render. Used only if a specific function of the renderer must be executed
	 * outside of render(), such as during assisted manual rendering.
	 *
	 * @param Nette\Forms\Form $form
	 */
	public function attachForm(Nette\Forms\Form $form): void
	{
		$this->form = $form;
	}


	/**
	 * Turns configuration or and existing element and configures it appropriately
	 *
	 * @param $config array|string top-level config key
	 * @param $el     Html|null elem to config.
	 *
	 * @return Html|null
	 */
	public function configElem($config, $el = null): ?Html
	{
		if (is_scalar($config)) {
			$config = $this->fetchConfig($config);
		}

		// first define which element we want to work with
		if (isset($config[Cnf::ELEMENT_NAME])) {
			$name = $config[Cnf::ELEMENT_NAME];
			if (!$el) {
				// element does not exist, so create it
				$el = Html::el($name);
			} else {
				// element exists, but we want to change its name
				$el->setName($name);
			}
		}

		if ($el instanceof Html && $el != null) {
			// if el is defined, we can configure it accordingly

			// go through all config and configure element accordingly
			foreach ($config as $key => $value) {
				if (in_array($key, [Cnf::CLASS_SET, Cnf::CLASS_ADD, Cnf::CLASS_ADD, Cnf::CLASS_REMOVE], true)) {
					// we'll be working with classes, we must standardize everything to arrays, not strings for the sake of sanity
					if (!is_array($value)) {
						// configuration may contain classes as strings, but we always work with arrays
						$value = [$value];
					}

					$origClass = $el->getAttribute('class');
					$newClass = $origClass;
					if ($origClass === null) {
						// class is not set
						$newClass = [];
					} elseif (!is_array($origClass)) {
						// class is set, but not a array
						$newClass = explode(' ', $el->getAttribute('class'));
					}
					$el->setAttribute('class', $newClass);
					$origClass = $newClass;
				}

				if ($key === Cnf::CLASS_SET) {
					$el->setAttribute('class', $value);
				} elseif ($key === Cnf::CLASS_ADD && isset($origClass)) {
					$el->setAttribute('class', array_merge($origClass, $value));
				} elseif ($key === Cnf::CLASS_REMOVE && isset($origClass)) {
					$el->setAttribute('class', array_diff($origClass, $value));
				} elseif ($key === Cnf::ATTRIBUTES) {
					foreach ($value as $attr => $attrVal) {
						$el->setAttribute($attr, $attrVal);
					}
				}
			}
		}

		// el may be null, but maybe it has a container defined
		if (isset($config[Cnf::CONTAINER])) {
			$container = $this->configElem($config[Cnf::CONTAINER], null);
			if ($container !== null && $el !== null) {
				$elClone = clone $el;
				$container->setHtml($elClone);
			}
			$el = $container;
		}

		return $el;
	}


	public function getConfig(): array
	{
		return [
			Cnf::FORM => [],
			Cnf::GROUP => [
				Cnf::ELEMENT_NAME => 'fieldset',
			],
			Cnf::GROUP_LABEL => [
				Cnf::ELEMENT_NAME => 'legend',
			],

			Cnf::GRID_ROW => [
				Cnf::ELEMENT_NAME => 'div',
				Cnf::CLASS_SET => 'form-row',
			],
			Cnf::GRID_CELL => [
				Cnf::ELEMENT_NAME => 'div',
			],

			Cnf::FORM_OWN_ERRORS => [],
			Cnf::FORM_OWN_ERROR => [
				Cnf::ELEMENT_NAME => 'div',
				Cnf::CLASS_SET => ['alert', 'alert-danger'],
			],

			Cnf::PAIR => [
				Cnf::ELEMENT_NAME => 'div',
				Cnf::CLASS_SET => 'form-group',
			],
			Cnf::LABEL => [
				Cnf::ELEMENT_NAME => 'label',
			],

			Cnf::INPUT => [],
			// inputs which are normally inline elements (after bootstrap classes are applied)
			Cnf::INPUT_VALID => [
				Cnf::CLASS_ADD => 'is-valid',
			],
			Cnf::INPUT_INVALID => [
				Cnf::CLASS_ADD => 'is-invalid',
			],

			Cnf::DESCRIPTION => [
				Cnf::ELEMENT_NAME => 'small',
				Cnf::CLASS_SET => ['form-text', 'text-muted'],
			],

			Cnf::FEEDBACK => [
				Cnf::ELEMENT_NAME => 'div',
			],
			Cnf::FEEDBACK_VALID => [
				Cnf::CLASS_ADD => 'valid-feedback',
			],
			Cnf::FEEDBACK_INVALID => [
				Cnf::CLASS_ADD => 'invalid-feedback',
			],

			// empty wrapper,  but it gets utilized in side-by side and inline mode
			Cnf::NON_LABEL => [
				Cnf::ELEMENT_NAME => null,
			],
		];
	}


	public function getConfigOverride(): array
	{
		if ($this->gridBreakPoint != null) {
			$labelColClass = "col-{$this->gridBreakPoint}-{$this->labelColumns}";
			$nonLabelColClass = "col-{$this->gridBreakPoint}-{$this->controlColumns}";
		} else {
			$labelColClass = "col-{$this->labelColumns}";
			$nonLabelColClass = "col-{$this->controlColumns}";
		}

		return [
			RenderMode::INLINE => [
				Cnf::FORM => [
					Cnf::CLASS_ADD => 'form-inline',
				],
				Cnf::NON_LABEL => [
					Cnf::ELEMENT_NAME => 'div',
				],
			],
			RenderMode::SIDE_BY_SIDE_MODE => [
				Cnf::PAIR => [
					Cnf::CLASS_ADD => 'row',
				],
				Cnf::LABEL => [
					Cnf::CLASS_ADD => $labelColClass,
				],
				Cnf::NON_LABEL => [
					Cnf::ELEMENT_NAME => 'div',
					Cnf::CLASS_SET => $nonLabelColClass,
				],
			],
			RenderMode::VERTICAL_MODE => [],
		];
	}


	/**
	 * @return string
	 */
	public function getGridBreakPoint(): string
	{
		return $this->gridBreakPoint;
	}


	/**
	 * @param string $gridBreakPoint null for none
	 *
	 * @return BootstrapRenderer
	 */
	public function setGridBreakPoint($gridBreakPoint): self
	{
		$this->gridBreakPoint = $gridBreakPoint;

		return $this;
	}


	/**
	 * Returns render mode
	 * @return int
	 * @see RenderMode
	 */
	public function getMode(): RenderMode
	{
		return $this->renderMode;
	}


	/**
	 * @return bool
	 * @see BootstrapRenderer::$groupHidden
	 */
	public function isGroupHidden(): bool
	{
		return $this->groupHidden;
	}


	/**
	 * @param bool $groupHidden
	 *
	 * @return BootstrapRenderer
	 * @see BootstrapRenderer::$groupHidden
	 */
	public function setGroupHidden($groupHidden): self
	{
		$this->groupHidden = $groupHidden;

		return $this;
	}


	/**
	 * Provides complete form rendering.
	 *
	 * @param \Nette\Forms\Form $form
	 * @param null              $mode
	 *
	 * @return string
	 */
	public function render(Nette\Forms\Form $form, $mode = null): string
	{
		$this->attachForm($form);

		$s = '';
		$s .= $this->renderBegin();
		$s .= $this->renderFeedback();
		$s .= $this->renderBody();
		$s .= $this->renderEnd();

		return $s;
	}


	/**
	 * Renders form begin.
	 * @return string
	 */
	public function renderBegin(): string
	{
		foreach ($this->form->getControls() as $control) {
			$control->setOption(RendererOptions::_RENDERED, false);
		}

		$prototype = $this->form->getElementPrototype();
		$prototype = $this->configElem('form', $prototype);

		if ($this->form->isMethod('get')) {
			$el = $prototype;
			$query = parse_url($el->action, PHP_URL_QUERY);
			$el->action = str_replace("?$query", '', $el->action);
			$s = '';
			foreach (preg_split('#[;&]#', $query, null, PREG_SPLIT_NO_EMPTY) as $param) {
				$parts = explode('=', $param, 2);
				$name = urldecode($parts[0]);
				if (!isset($this->form[$name])) {
					$s .= Html::el('input', ['type' => 'hidden', 'name' => $name, 'value' => urldecode($parts[1])]);
				}
			}

			return $el->startTag() . "\n$s";
		} else {
			return $prototype->startTag();
		}
	}


	/**
	 * Renders form body.
	 * @return string
	 */
	public function renderBody(): string
	{
		$translator = $this->form->getTranslator();

		// first render groups. They will mark their controls as rendered
		$groups = Html::el();
		foreach ($this->form->getGroups() as $group) {
			if ($group->getControls() === [] || empty($group->getOption(RendererOptions::VISUAL))) {
				continue;
			}

			//region getting container
			$container = $group->getOption(RendererOptions::CONTAINER, null);
			if (is_string($container)) {
				$container = $this->configElem(Cnf::GROUP, Html::el($container));
			} elseif ($container instanceof Html) {
				$container = $this->configElem(Cnf::GROUP, $container);
			} else {
				$container = $this->getElem(Cnf::GROUP);
			}

			$container->setAttribute('id', $group->getOption(RendererOptions::ID));

			//endregion

			//region label
			$label = $group->getOption(RendererOptions::LABEL);
			if ($label instanceof Html) {
				$label = $this->configElem(Cnf::GROUP_LABEL, $label);
			} elseif (is_string($label)) {
				if ($translator !== null) {
					$label = $translator->translate($label);
				}
				$labelHtml = $this->getElem(Cnf::GROUP_LABEL);
				$labelHtml->setText($label);
				$label = $labelHtml;
			}
			//endregion

			if (is_scalar($label) || $label instanceof Html) {
				$container->addHtml($label);
			}

			$controls = $this->renderControls($group);
			$container->addHtml($controls);

			$groups->addHtml($container);
		}
		// we now know which ones to skip, so render the rest
		$formControls = $this->renderControls($this->form);

		$out = Html::el();
		$out->addHtml($formControls);
		$out->addHtml($groups);

		return (string) $out;
	}


	/**
	 * Renders 'control' part of visual row of controls.
	 *
	 * @param \Nette\Forms\IControl $control
	 *
	 * @return string
	 */
	public function renderControl(Nette\Forms\IControl $control): string
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$controlHtml = $control->getControl();
		/** @noinspection PhpUndefinedMethodInspection */
		$control->setOption(RendererOptions::_RENDERED, true);
		/** @noinspection PhpUndefinedMethodInspection */
		if (($this->form->showValidation || $control->hasErrors()) && $control instanceof IValidationInput) {
			$controlHtml = $control->showValidation($controlHtml);
		}
		$controlHtml = $this->configElem(Cnf::INPUT, $controlHtml);

		return (string) $controlHtml;
	}


	/**
	 * Renders group of controls.
	 *
	 * @param Nette\Forms\Container|Nette\Forms\ControlGroup
	 *
	 * @return string
	 */
	public function renderControls($parent): string
	{
		if (!($parent instanceof Nette\Forms\Container || $parent instanceof Nette\Forms\ControlGroup)) {
			throw new Nette\InvalidArgumentException('Argument must be Nette\Forms\Container or Nette\Forms\ControlGroup instance.');
		}
		$html = Html::el();
		$hidden = Html::el();

		// note that these are NOT form groups, these are groups specified to group
		foreach ($parent->getControls() as $control) {
			if ($control->getOption(RendererOptions::_RENDERED, false)) {
				continue;
			}

			if ($control instanceof BootstrapRow) {
				$html->addHtml($control->render());
			} else {
				if ($control->getOption(RendererOptions::TYPE) == 'hidden') {
					$isHidden = true;
					$pairHtml = $this->renderControl($control);
				} else {
					$pairHtml = $this->renderPair($control);
					$isHidden = false;
				}

				if ($this->groupHidden && $isHidden) {
					$hidden->addHtml($pairHtml);
				} else {
					$html->addHtml($pairHtml);
				}
			}
		}

		$html->addHtml($hidden);

		return (string) $html;
	}


	/**
	 * Renders form end.
	 * @return string
	 */
	public function renderEnd(): string
	{
		return $this->form->getElementPrototype()
				->endTag() . "\n";
	}


	/**
	 * Renders 'label' part of visual row of controls.
	 *
	 * @param \Nette\Forms\IControl $control
	 *
	 * @return Html
	 */
	public function renderLabel(Nette\Forms\IControl $control): Html
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$controlLabel = $control->getLabel();
		if ($controlLabel instanceof Html && $controlLabel->getName() == 'label') {
			// the control has already provided us with the element, no need to create our own
			$controlLabel = $this->configElem(Cnf::LABEL, $controlLabel);
			// just configure it to suit our needs

			$labelHtml = $controlLabel;
		} elseif ($controlLabel === null) {
			return Html::el();
		} else {
			// the control doesn't give us <label>, se we'll create our own
			$labelHtml = $this->getElem(Cnf::LABEL);
			if ($controlLabel) {
				$labelHtml->setHtml($controlLabel);
			}
		}

		return $labelHtml;
	}


	/**
	 * Renders single visual row.
	 *
	 * @param \Nette\Forms\IControl $control
	 *
	 * @return string
	 */
	public function renderPair(Nette\Forms\IControl $control): string
	{
		$pairHtml = $this->configElem(Cnf::PAIR);
		/** @noinspection PhpUndefinedMethodInspection */
		/** @noinspection PhpUndefinedFieldInspection */
		$pairHtml->id = $control->getOption(RendererOptions::ID);

		$labelHtml = $this->renderLabel($control);
		$pairHtml->addHtml($labelHtml);

		$nonLabel = $this->getElem(Cnf::NON_LABEL);

		//region non-label parts
		$controlHtml = $this->renderControl($control);
		$feedbackHtml = $this->renderFeedback($control);
		$descriptionHtml = $this->renderDescription($control);

		$nonLabel->addHtml($controlHtml);
		$nonLabel->addHtml($feedbackHtml);
		$nonLabel->addHtml($descriptionHtml);
		//endregion

		$pairHtml->addHtml($nonLabel);

		return $pairHtml->render(0);
	}


	/**
	 * Set how many of Bootstrap rows shall the label and control occupy
	 *
	 * @param int      $label
	 * @param int|null $control
	 */
	public function setColumns($label, $control = null): void
	{
		if ($control === null) {
			$control = 12 - $label;
		}

		$this->labelColumns = $label;
		$this->controlColumns = $control;
	}


	/**
	 * Sets render mode
	 *
	 * @param int $renderMode RenderMode
	 *
	 * @see RenderMode
	 */
	public function setMode(int $renderMode): void
	{
		$this->renderMode = $renderMode;
	}


	/**
	 * Fetch config tailored for current render mode
	 *
	 * @param string $key first-level key of $this->config
	 *
	 * @return array
	 */
	protected function fetchConfig($key): array
	{
		$config = $this->config[$key];

		if (isset($this->configOverride[$this->renderMode][$key])) {
			$override = $this->configOverride[$this->renderMode][$key];
			$config = array_merge($config, $override);
		}

		return $config;
	}


	/**
	 * Get element based on its first-level key
	 *
	 * @param string $key
	 * @param array ...$additionalKeys config will be overridden in this order
	 *
	 * @return Html|null
	 */
	protected function getElem(string $key, ...$additionalKeys): ?Html
	{
		$el = $this->configElem($key, Html::el());

		foreach ($additionalKeys as $additionalKey) {
			$config = $this->fetchConfig($additionalKey);
			$el = $this->configElem($config, $el);
		}

		return $el;
	}


	/**
	 * Renders control description (help text)
	 *
	 * @param Nette\Forms\IControl $control
	 *
	 * @return Html|null
	 */
	protected function renderDescription(Nette\Forms\IControl $control): ?Html
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$description = $control->getOption(RendererOptions::DESCRIPTION);
		if (is_string($description)) {
			if ($control instanceof Nette\Forms\Controls\BaseControl) {
				$description = $control->translate($description);
			}
		} elseif (!$description instanceof Html) {
			$description = '';
		}

		if (is_scalar($description)) {
			$el = $this->getElem(Cnf::DESCRIPTION);
			$el->setHtml($description);

			return $el;
		} else {
			return null;
		}
	}


	/**
	 * Renders valid or invalid feedback of form or control
	 *
	 * @param Nette\Forms\Controls\BaseControl|null $control null = whole form
	 *
	 * @return Html|null
	 */
	protected function renderFeedback($control = null): ?Html
	{
		$validationHtml = null;
		$isValid = true;
		$showFeedback = false;
		$messages = [];

		if ($control instanceof Nette\Forms\IControl) {
			// specific control

			if ($control->hasErrors()) {
				// control is invalid, mark it that way
				$isValid = false;
				$showFeedback = true;
				$messages = $control->getErrors();
			} elseif ($this->form->showValidation) {
				$isValid = true;
				// control is valid and we want to explicitly show that it's valid
				$message = $control->getOption(RendererOptions::FEEDBACK_VALID);
				if (is_scalar($message)) {
					$messages = [$message];
					$showFeedback = true;
				} else {
					$showFeedback = false;
				}
			}

			if ($showFeedback && count($messages)) {
				$el = $isValid ? $this->getElem(Cnf::FEEDBACK, Cnf::FEEDBACK_VALID) : $this->getElem(Cnf::FEEDBACK, Cnf::FEEDBACK_INVALID);

				foreach ($messages as $message) {
					if ($message instanceof Html) {
						$el->addHtml($message);
					} else {
						$el->addText($message);
					}
					$el->addHtml('<br>');
				}

				return $el;
			} else {
				return null;
			}
		} elseif ($control === null) {
			// whole form
			$form = $this->form;

			if ($form->hasErrors()) {
				$showFeedback = true;
				$messages = $form->getOwnErrors();
			} else {
				$showFeedback = false;
				// this doesn't make sense, form is sent if it's valid
			}

			if ($showFeedback && count($messages)) {
				$el = $this->getElem(Cnf::FORM_OWN_ERRORS);
				$msgTemplate = $this->getElem(Cnf::FORM_OWN_ERROR);

				foreach ($messages as $message) {
					$messageHtml = clone $msgTemplate;
					if ($message instanceof Html) {
						$messageHtml->setHtml($message);
					} else {
						$messageHtml->setText($message);
					}

					$el->addHtml($messageHtml);
				}

				return $el;
			} else {
				return null;
			}
		} else {
			throw new Nette\NotImplementedException('renderer is unable to render feedback for ' . gettype($control));
		}
	}
}
