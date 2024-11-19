<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\View\Helper;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\View\Form\ContextFactory;
use Cake\View\Form\ContextInterface;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;
use Cake\View\Widget\WidgetLocator;
use Cake\View\Widget\WidgetRegistry;
use DateTime;
use RuntimeException;
use Traversable;

/**
 * Form helper library.
 *
 * Automatic generation of HTML FORMs from given data.
 *
 * @method string text(string $fieldName, array $options = [])
 * @method string number(string $fieldName, array $options = [])
 * @method string email(string $fieldName, array $options = [])
 * @method string password(string $fieldName, array $options = [])
 * @method string search(string $fieldName, array $options = [])
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 * @link https://book.cakephp.org/3/en/views/helpers/form.html
 */
class FormHelper extends Helper
{
    use IdGeneratorTrait;
    use SecureFieldTokenTrait;
    use StringTemplateTrait;

    /**
     * Other helpers used by FormHelper
     *
     * @var array
     */
    public $helpers = ['file.php', 'file.php'];

    /**
     * The various pickers that make up a datetime picker.
     *
     * @var array
     */
    protected $_datetimeParts = ['file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php'];

    /**
     * Special options used for datetime inputs.
     *
     * @var array
     */
    protected $_datetimeOptions = [
        'file.php', 'file.php', 'file.php', 'file.php', 'file.php',
        'file.php', 'file.php', 'file.php',
    ];

    /**
     * Default config for the helper.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'file.php' => null,
        'file.php' => 'file.php',
        'file.php' => [
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
        ],
        'file.php' => [
            // Used for button elements in button().
            'file.php' => 'file.php',
            // Used for checkboxes in checkbox() and multiCheckbox().
            'file.php' => 'file.php',
            // Input group wrapper for checkboxes created via control().
            'file.php' => 'file.php',
            // Wrapper container for checkboxes.
            'file.php' => 'file.php',
            // Widget ordering for date/time/datetime pickers.
            'file.php' => 'file.php',
            // Error message wrapper elements.
            'file.php' => 'file.php',
            // Container for error items.
            'file.php' => 'file.php',
            // Error item wrapper.
            'file.php' => 'file.php',
            // File input used by file().
            'file.php' => 'file.php',
            // Fieldset element used by allControls().
            'file.php' => 'file.php',
            // Open tag used by create().
            'file.php' => 'file.php',
            // Close tag used by end().
            'file.php' => 'file.php',
            // General grouping container for control(). Defines input/label ordering.
            'file.php' => 'file.php',
            // Wrapper content used to hide other content.
            'file.php' => 'file.php',
            // Generic input element.
            'file.php' => 'file.php',
            // Submit input element.
            'file.php' => 'file.php',
            // Container element used by control().
            'file.php' => 'file.php',
            // Container element used by control() when a field has an error.
            'file.php' => 'file.php',
            // Label element when inputs are not nested inside the label.
            'file.php' => 'file.php',
            // Label element used for radio and multi-checkbox inputs.
            'file.php' => 'file.php',
            // Legends created by allControls()
            'file.php' => 'file.php',
            // Multi-Checkbox input set title element.
            'file.php' => 'file.php',
            // Multi-Checkbox wrapping container.
            'file.php' => 'file.php',
            // Option element used in select pickers.
            'file.php' => 'file.php',
            // Option group element used in select pickers.
            'file.php' => 'file.php',
            // Select element,
            'file.php' => 'file.php',
            // Multi-select element,
            'file.php' => 'file.php',
            // Radio input element,
            'file.php' => 'file.php',
            // Wrapping container for radio input/label,
            'file.php' => 'file.php',
            // Textarea input element,
            'file.php' => 'file.php',
            // Container for submit buttons.
            'file.php' => 'file.php',
            // Confirm javascript template for postLink()
            'file.php' => 'file.php',
            // selected class
            'file.php' => 'file.php',
        ],
        // set HTML5 validation message to custom required/empty messages
        'file.php' => false,
    ];

    /**
     * Default widgets
     *
     * @var array
     */
    protected $_defaultWidgets = [
        'file.php' => ['file.php'],
        'file.php' => ['file.php'],
        'file.php' => ['file.php'],
        'file.php' => ['file.php'],
        'file.php' => ['file.php'],
        'file.php' => ['file.php', 'file.php'],
        'file.php' => ['file.php', 'file.php'],
        'file.php' => ['file.php'],
        'file.php' => ['file.php'],
        'file.php' => ['file.php', 'file.php'],
        'file.php' => ['file.php'],
    ];

    /**
     * List of fields created, used with secure forms.
     *
     * @var string[]
     */
    public $fields = [];

    /**
     * Constant used internally to skip the securing process,
     * and neither add the field to the hash or to the unlocked fields.
     *
     * @var string
     */
    const SECURE_SKIP = 'file.php';

    /**
     * Defines the type of form being created. Set by FormHelper::create().
     *
     * @var string|null
     */
    public $requestType;

    /**
     * An array of field names that have been excluded from
     * the Token hash used by SecurityComponent'file.php''file.php'context'file.php'radio'file.php'multicheckbox'file.php'date'file.php'time'file.php'datetime'file.php'registry'file.php'`registry` config key is deprecated in FormHelper, use `locator` instead.'file.php'locator'file.php'registry'file.php'registry'file.php'locator'file.php'locator'file.php'locator'file.php'widgets'file.php'widgets'file.php'widgets'file.php'widgets'file.php'widgets'file.php'widgets'file.php'groupedInputTypes'file.php'groupedInputTypes'file.php'groupedInputTypes'file.php'idPrefix'file.php'widgetRegistry is deprecated, use widgetLocator instead.'file.php's isCreate() method returns false, a PUT request will be done.
     * - `method` Set the form'file.php't need to change the controller from the current request'file.php'url'file.php'action'file.php'App.encoding'file.php'table'file.php''file.php'Using `string` or `bool` for $context is deprecated, use `null` to make a context-less form.'file.php'context'file.php'context'file.php'context'file.php'entity'file.php'context'file.php'context'file.php'type'file.php'post'file.php'put'file.php'action'file.php'url'file.php'encoding'file.php'App.encoding'file.php'templates'file.php'idPrefix'file.php'valueSources'file.php'action'file.php'Using key `action` is deprecated, use `url` directly instead.'file.php'valueSources'file.php'valueSources'file.php'valueSources'file.php'idPrefix'file.php'idPrefix'file.php'templates'file.php'templates'file.php'load'file.php'add'file.php'templates'file.php'templates'file.php'action'file.php'url'file.php'url'file.php'action'file.php'idPrefix'file.php'type'file.php'get'file.php'method'file.php'get'file.php'file'file.php'enctype'file.php'multipart/form-data'file.php'type'file.php'post'file.php'put'file.php'post'file.php'put'file.php'delete'file.php'patch'file.php'_method'file.php'name'file.php'_method'file.php'value'file.php'type'file.php'secure'file.php'method'file.php'post'file.php'method'file.php'method'file.php'method'file.php'enctype'file.php'enctype'file.php'enctype'file.php'type'file.php'encoding'file.php'accept-charset'file.php'encoding'file.php'type'file.php'encoding'file.php'get'file.php'hiddenBlock'file.php'content'file.php'action'file.php'escape'file.php'formStart'file.php'attrs'file.php'templateVars'file.php'templateVars'file.php'templateVars'file.php'action'file.php'url'file.php'url'file.php'url'file.php'url'file.php'_name'file.php'url'file.php'action'file.php'url'file.php'action'file.php'url'file.php'action'file.php'action'file.php'plugin'file.php'controller'file.php'controller'file.php'action'file.php'action'file.php'url'file.php'?'file.php''file.php''file.php'_Token.unlockedFields'file.php'_Token.unlockedFields'file.php'_csrfToken'file.php''file.php'_csrfToken'file.php'value'file.php'_csrfToken'file.php'secure'file.php'autocomplete'file.php'off'file.php''file.php'get'file.php'_Token'file.php'formEnd'file.php'context'file.php'idPrefix'file.php'form'file.php'_Token'file.php''file.php'debug'file.php'debugSecurity'file.php'debugSecurity'file.php'debugSecurity'file.php'secure'file.php'autocomplete'file.php'off'file.php'value'file.php'fields'file.php'_Token.fields'file.php'value'file.php'unlocked'file.php'_Token.unlocked'file.php'value'file.php'_Token.debug'file.php'hiddenBlock'file.php'content'file.php'0'file.php'.'file.php'.'file.php'.'file.php'/(\.\d+)+$/'file.php''file.php''file.php''file.php'._ids'file.php'escape'file.php''file.php'escape'file.php'escape'file.php'errorItem'file.php'text'file.php'errorList'file.php'content'file.php''file.php'error'file.php'content'file.php'published'file.php'published'file.php'Publish'file.php'published'file.php'Publish'file.php'for'file.php'post-publish'file.php'published'file.php'Publish'file.php'for'file.php'published'file.php'input'file.php'published'file.php'._ids'file.php'.'file.php'.'file.php'_id'file.php'for'file.php'for'file.php'for'file.php'for'file.php'text'file.php'input'file.php'input'file.php'input'file.php'nestingLabel'file.php'label'file.php'name'file.php'label'file.php'custom label'file.php'title'file.php'FormHelper::allInputs() is deprecated. 'file.php'Use FormHelper::allControls() instead.'file.php'name'file.php'label'file.php'custom label'file.php'email'file.php''file.php'FormHelper::inputs() is deprecated. 'file.php'Use FormHelper::controls() instead.'file.php''file.php'legend'file.php'legend'file.php'fieldset'file.php'fieldset'file.php'controller'file.php'cake'file.php'Edit {0}'file.php'cake'file.php'New {0}'file.php'legend'file.php'text'file.php'content'file.php'attrs'file.php''file.php'attrs'file.php'fieldset'file.php's options
     * will be treated as a regular HTML attribute for the generated input.
     *
     * - `type` - Force the type of widget you want. e.g. `type => 'file.php'`
     * - `label` - Either a string label, or an array of options for the label. See FormHelper::label().
     * - `options` - For widgets that take options e.g. radio, select.
     * - `error` - Control the error message that is produced. Set to `false` to disable any kind of error reporting (field
     *    error and error messages).
     * - `empty` - String or boolean to enable empty select box options.
     * - `nestedInput` - Used with checkbox and radio inputs. Set to false to render inputs outside of label
     *   elements. Can be set to true on any input to force the input inside the label. If you
     *   enable this option for radio buttons you will also need to modify the default `radioWrapper` template.
     * - `templates` - The templates you want to use for this input. Any templates will be merged on top of
     *   the already loaded templates. This option can either be a filename in /config that contains
     *   the templates you want to load, or an array of templates to use.
     * - `labelOptions` - Either `false` to disable label around nestedWidgets e.g. radio, multicheckbox or an array
     *   of attributes for the label tag. `selected` will be added to any classes e.g. `class => 'file.php'` where
     *   widget is checked
     *
     * @param string $fieldName This should be "modelname.fieldname"
     * @param array $options Each type of input takes different options.
     * @return string Completed form widget.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-form-inputs
     */
    public function control($fieldName, array $options = [])
    {
        $options += [
            'file.php' => null,
            'file.php' => null,
            'file.php' => null,
            'file.php' => null,
            'file.php' => null,
            'file.php' => [],
            'file.php' => [],
            'file.php' => true,
        ];
        $options = $this->_parseOptions($fieldName, $options);
        $options += ['file.php' => $this->_domId($fieldName)];

        $templater = $this->templater();
        $newTemplates = $options['file.php'];

        if ($newTemplates) {
            $templater->push();
            $templateMethod = is_string($options['file.php']) ? 'file.php' : 'file.php';
            $templater->{$templateMethod}($options['file.php']);
        }
        unset($options['file.php']);

        $error = null;
        $errorSuffix = 'file.php';
        if ($options['file.php'] !== 'file.php' && $options['file.php'] !== false) {
            if (is_array($options['file.php'])) {
                $error = $this->error($fieldName, $options['file.php'], $options['file.php']);
            } else {
                $error = $this->error($fieldName, $options['file.php']);
            }
            $errorSuffix = empty($error) ? 'file.php' : 'file.php';
            unset($options['file.php']);
        }

        $label = $options['file.php'];
        unset($options['file.php']);

        $labelOptions = $options['file.php'];
        unset($options['file.php']);

        $nestedInput = false;
        if ($options['file.php'] === 'file.php') {
            $nestedInput = true;
        }
        $nestedInput = isset($options['file.php']) ? $options['file.php'] : $nestedInput;
        unset($options['file.php']);

        if ($nestedInput === true && $options['file.php'] === 'file.php' && !array_key_exists('file.php', $options) && $label !== false) {
            $options['file.php'] = 'file.php';
        }

        $input = $this->_getInput($fieldName, $options + ['file.php' => $labelOptions]);
        if ($options['file.php'] === 'file.php' || $options['file.php'] === 'file.php') {
            if ($newTemplates) {
                $templater->pop();
            }

            return $input;
        }

        $label = $this->_getLabel($fieldName, compact('file.php', 'file.php', 'file.php', 'file.php') + $options);
        if ($nestedInput) {
            $result = $this->_groupTemplate(compact('file.php', 'file.php', 'file.php'));
        } else {
            $result = $this->_groupTemplate(compact('file.php', 'file.php', 'file.php', 'file.php'));
        }
        $result = $this->_inputContainerTemplate([
            'file.php' => $result,
            'file.php' => $error,
            'file.php' => $errorSuffix,
            'file.php' => $options,
        ]);

        if ($newTemplates) {
            $templater->pop();
        }

        return $result;
    }

    /**
     * Generates a form control element complete with label and wrapper div.
     *
     * @param string $fieldName This should be "modelname.fieldname"
     * @param array $options Each type of input takes different options.
     * @return string Completed form widget.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-form-inputs
     * @deprecated 3.4.0 Use FormHelper::control() instead.
     */
    public function input($fieldName, array $options = [])
    {
        deprecationWarning(
            'file.php' .
            'file.php'
        );

        return $this->control($fieldName, $options);
    }

    /**
     * Generates an group template element
     *
     * @param array $options The options for group template
     * @return string The generated group template
     */
    protected function _groupTemplate($options)
    {
        $groupTemplate = $options['file.php']['file.php'] . 'file.php';
        if (!$this->templater()->get($groupTemplate)) {
            $groupTemplate = 'file.php';
        }

        return $this->formatTemplate($groupTemplate, [
            'file.php' => isset($options['file.php']) ? $options['file.php'] : [],
            'file.php' => $options['file.php'],
            'file.php' => $options['file.php'],
            'file.php' => isset($options['file.php']['file.php']) ? $options['file.php']['file.php'] : [],
        ]);
    }

    /**
     * Generates an input container template
     *
     * @param array $options The options for input container template
     * @return string The generated input container template
     */
    protected function _inputContainerTemplate($options)
    {
        $inputContainerTemplate = $options['file.php']['file.php'] . 'file.php' . $options['file.php'];
        if (!$this->templater()->get($inputContainerTemplate)) {
            $inputContainerTemplate = 'file.php' . $options['file.php'];
        }

        return $this->formatTemplate($inputContainerTemplate, [
            'file.php' => $options['file.php'],
            'file.php' => $options['file.php'],
            'file.php' => $options['file.php']['file.php'] ? 'file.php' : 'file.php',
            'file.php' => $options['file.php']['file.php'],
            'file.php' => isset($options['file.php']['file.php']) ? $options['file.php']['file.php'] : [],
        ]);
    }

    /**
     * Generates an input element
     *
     * @param string $fieldName the field name
     * @param array $options The options for the input element
     * @return string The generated input element
     */
    protected function _getInput($fieldName, $options)
    {
        $label = $options['file.php'];
        unset($options['file.php']);
        switch (strtolower($options['file.php'])) {
            case 'file.php':
                $opts = $options['file.php'];
                unset($options['file.php']);

                return $this->select($fieldName, $opts, $options + ['file.php' => $label]);
            case 'file.php':
                $opts = $options['file.php'];
                unset($options['file.php']);

                return $this->radio($fieldName, $opts, $options + ['file.php' => $label]);
            case 'file.php':
                $opts = $options['file.php'];
                unset($options['file.php']);

                return $this->multiCheckbox($fieldName, $opts, $options + ['file.php' => $label]);
            case 'file.php':
                throw new RuntimeException("Invalid type 'file.php' used for field 'file.php'");

            default:
                return $this->{$options['file.php']}($fieldName, $options);
        }
    }

    /**
     * Generates input options array
     *
     * @param string $fieldName The name of the field to parse options for.
     * @param array $options Options list.
     * @return array Options
     */
    protected function _parseOptions($fieldName, $options)
    {
        $needsMagicType = false;
        if (empty($options['file.php'])) {
            $needsMagicType = true;
            $options['file.php'] = $this->_inputType($fieldName, $options);
        }

        $options = $this->_magicOptions($fieldName, $options, $needsMagicType);

        return $options;
    }

    /**
     * Returns the input type that was guessed for the provided fieldName,
     * based on the internal type it is associated too, its name and the
     * variables that can be found in the view template
     *
     * @param string $fieldName the name of the field to guess a type for
     * @param array $options the options passed to the input method
     * @return string
     */
    protected function _inputType($fieldName, $options)
    {
        $context = $this->_getContext();

        if ($context->isPrimaryKey($fieldName)) {
            return 'file.php';
        }

        if (substr($fieldName, -3) === 'file.php') {
            return 'file.php';
        }

        $internalType = $context->type($fieldName);
        $map = $this->_config['file.php'];
        $type = isset($map[$internalType]) ? $map[$internalType] : 'file.php';
        $fieldName = array_slice(explode('file.php', $fieldName), -1)[0];

        switch (true) {
            case isset($options['file.php']):
                return 'file.php';
            case isset($options['file.php']):
                return 'file.php';
            case in_array($fieldName, ['file.php', 'file.php']):
                return 'file.php';
            case in_array($fieldName, ['file.php', 'file.php', 'file.php']):
                return 'file.php';
            case $fieldName === 'file.php':
                return 'file.php';
            case isset($options['file.php']) || isset($options['file.php']):
                return 'file.php';
        }

        return $type;
    }

    /**
     * Selects the variable containing the options for a select field if present,
     * and sets the value to the 'file.php' key in the options array.
     *
     * @param string $fieldName The name of the field to find options for.
     * @param array $options Options list.
     * @return array
     */
    protected function _optionsOptions($fieldName, $options)
    {
        if (isset($options['file.php'])) {
            return $options;
        }

        $pluralize = true;
        if (substr($fieldName, -5) === 'file.php') {
            $fieldName = substr($fieldName, 0, -5);
            $pluralize = false;
        } elseif (substr($fieldName, -3) === 'file.php') {
            $fieldName = substr($fieldName, 0, -3);
        }
        $fieldName = array_slice(explode('file.php', $fieldName), -1)[0];

        $varName = Inflector::variable(
            $pluralize ? Inflector::pluralize($fieldName) : $fieldName
        );
        $varOptions = $this->_View->get($varName);
        if (!is_array($varOptions) && !($varOptions instanceof Traversable)) {
            return $options;
        }
        if ($options['file.php'] !== 'file.php') {
            $options['file.php'] = 'file.php';
        }
        $options['file.php'] = $varOptions;

        return $options;
    }

    /**
     * Magically set option type and corresponding options
     *
     * @param string $fieldName The name of the field to generate options for.
     * @param array $options Options list.
     * @param bool $allowOverride Whether or not it is allowed for this method to
     * overwrite the 'file.php' key in options.
     * @return array
     */
    protected function _magicOptions($fieldName, $options, $allowOverride)
    {
        $context = $this->_getContext();

        $options += [
            'file.php' => [],
        ];

        if (!isset($options['file.php']) && $options['file.php'] !== 'file.php') {
            $options['file.php'] = $context->isRequired($fieldName);
        }

        if (method_exists($context, 'file.php')) {
            $message = $context->getRequiredMessage($fieldName);
            $message = h($message);

            if ($options['file.php'] && $message) {
                $options['file.php']['file.php'] = $message;

                if ($this->getConfig('file.php')) {
                    $options['file.php'] = "this.setCustomValidity('file.php'); if (!this.validity.valid) this.setCustomValidity('file.php')";
                    $options['file.php'] = "this.setCustomValidity('file.php')";
                }
            }
        }

        $type = $context->type($fieldName);
        $fieldDef = $context->attributes($fieldName);

        if ($options['file.php'] === 'file.php' && !isset($options['file.php'])) {
            if ($type === 'file.php' && isset($fieldDef['file.php'])) {
                $decimalPlaces = $fieldDef['file.php'];
                $options['file.php'] = sprintf('file.php' . $decimalPlaces . 'file.php', pow(10, -1 * $decimalPlaces));
            } elseif ($type === 'file.php') {
                $options['file.php'] = 'file.php';
            }
        }

        $typesWithOptions = ['file.php', 'file.php', 'file.php', 'file.php'];
        $magicOptions = (in_array($options['file.php'], ['file.php', 'file.php']) || $allowOverride);
        if ($magicOptions && in_array($options['file.php'], $typesWithOptions)) {
            $options = $this->_optionsOptions($fieldName, $options);
        }

        if ($allowOverride && substr($fieldName, -5) === 'file.php') {
            $options['file.php'] = 'file.php';
            if (!isset($options['file.php']) || ($options['file.php'] && $options['file.php'] != 'file.php')) {
                $options['file.php'] = true;
            }
        }

        if ($options['file.php'] === 'file.php' && array_key_exists('file.php', $options)) {
            unset($options['file.php']);
        }

        $typesWithMaxLength = ['file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php'];
        if (
            !array_key_exists('file.php', $options)
            && in_array($options['file.php'], $typesWithMaxLength)
        ) {
            $maxLength = null;
            if (method_exists($context, 'file.php')) {
                $maxLength = $context->getMaxLength($fieldName);
            }

            if ($maxLength === null && !empty($fieldDef['file.php'])) {
                $maxLength = $fieldDef['file.php'];
            }

            if ($maxLength !== null) {
                $options['file.php'] = min($maxLength, 100000);
            }
        }

        if (in_array($options['file.php'], ['file.php', 'file.php', 'file.php', 'file.php'])) {
            $options += ['file.php' => false];
        }

        return $options;
    }

    /**
     * Generate label for input
     *
     * @param string $fieldName The name of the field to generate label for.
     * @param array $options Options list.
     * @return bool|string false or Generated label element
     */
    protected function _getLabel($fieldName, $options)
    {
        if ($options['file.php'] === 'file.php') {
            return false;
        }

        $label = null;
        if (isset($options['file.php'])) {
            $label = $options['file.php'];
        }

        if ($label === false && $options['file.php'] === 'file.php') {
            return $options['file.php'];
        }
        if ($label === false) {
            return false;
        }

        return $this->_inputLabel($fieldName, $label, $options);
    }

    /**
     * Extracts a single option from an options array.
     *
     * @param string $name The name of the option to pull out.
     * @param array $options The array of options you want to extract.
     * @param mixed $default The default option value
     * @return mixed the contents of the option or default
     */
    protected function _extractOption($name, $options, $default = null)
    {
        if (array_key_exists($name, $options)) {
            return $options[$name];
        }

        return $default;
    }

    /**
     * Generate a label for an input() call.
     *
     * $options can contain a hash of id overrides. These overrides will be
     * used instead of the generated values if present.
     *
     * @param string $fieldName The name of the field to generate label for.
     * @param string $label Label text.
     * @param array $options Options for the label element.
     * @return string Generated label element
     */
    protected function _inputLabel($fieldName, $label, $options)
    {
        $options += ['file.php' => null, 'file.php' => null, 'file.php' => false, 'file.php' => []];
        $labelAttributes = ['file.php' => $options['file.php']];
        if (is_array($label)) {
            $labelText = null;
            if (isset($label['file.php'])) {
                $labelText = $label['file.php'];
                unset($label['file.php']);
            }
            $labelAttributes = array_merge($labelAttributes, $label);
        } else {
            $labelText = $label;
        }

        $labelAttributes['file.php'] = $options['file.php'];
        if (in_array($options['file.php'], $this->_groupedInputTypes, true)) {
            $labelAttributes['file.php'] = false;
        }
        if ($options['file.php']) {
            $labelAttributes['file.php'] = $options['file.php'];
        }
        if (isset($options['file.php'])) {
            $labelAttributes['file.php'] = $options['file.php'];
        }

        return $this->label($fieldName, $labelText, $labelAttributes);
    }

    /**
     * Creates a checkbox input widget.
     *
     * ### Options:
     *
     * - `value` - the value of the checkbox
     * - `checked` - boolean indicate that this checkbox is checked.
     * - `hiddenField` - boolean to indicate if you want the results of checkbox() to include
     *    a hidden input with a value of 'file.php'.
     * - `disabled` - create a disabled input.
     * - `default` - Set the default value for the checkbox. This allows you to start checkboxes
     *    as checked, without having to check the POST data. A matching POST data value, will overwrite
     *    the default value.
     *
     * @param string $fieldName Name of a field, like this "modelname.fieldname"
     * @param array $options Array of HTML attributes.
     * @return string|array An HTML text input element.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-checkboxes
     */
    public function checkbox($fieldName, array $options = [])
    {
        $options += ['file.php' => true, 'file.php' => 1];

        // Work around value=>val translations.
        $value = $options['file.php'];
        unset($options['file.php']);
        $options = $this->_initInputField($fieldName, $options);
        $options['file.php'] = $value;

        $output = 'file.php';
        if ($options['file.php']) {
            $hiddenOptions = [
                'file.php' => $options['file.php'],
                'file.php' => $options['file.php'] !== true && $options['file.php'] !== 'file.php' ? $options['file.php'] : 'file.php',
                'file.php' => isset($options['file.php']) ? $options['file.php'] : null,
                'file.php' => false,
            ];
            if (isset($options['file.php']) && $options['file.php']) {
                $hiddenOptions['file.php'] = 'file.php';
            }
            $output = $this->hidden($fieldName, $hiddenOptions);
        }

        if ($options['file.php'] === 'file.php') {
            unset($options['file.php'], $options['file.php']);

            return ['file.php' => $output, 'file.php' => $this->widget('file.php', $options)];
        }
        unset($options['file.php'], $options['file.php']);

        return $output . $this->widget('file.php', $options);
    }

    /**
     * Creates a set of radio widgets.
     *
     * ### Attributes:
     *
     * - `value` - Indicates the value when this radio button is checked.
     * - `label` - Either `false` to disable label around the widget or an array of attributes for
     *    the label tag. `selected` will be added to any classes e.g. `'file.php' => 'file.php'` where widget
     *    is checked
     * - `hiddenField` - boolean to indicate if you want the results of radio() to include
     *    a hidden input with a value of 'file.php'. This is useful for creating radio sets that are non-continuous.
     * - `disabled` - Set to `true` or `disabled` to disable all the radio buttons. Use an array of
     *   values to disable specific radio buttons.
     * - `empty` - Set to `true` to create an input with the value 'file.php' as the first option. When `true`
     *   the radio label will be 'file.php'. Set this option to a string to control the label value.
     *
     * @param string $fieldName Name of a field, like this "modelname.fieldname"
     * @param array|\Traversable $options Radio button options array.
     * @param array $attributes Array of attributes.
     * @return string Completed radio widget set.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-radio-buttons
     */
    public function radio($fieldName, $options = [], array $attributes = [])
    {
        $attributes['file.php'] = $options;
        $attributes['file.php'] = $this->_idPrefix;
        $attributes = $this->_initInputField($fieldName, $attributes);

        $hiddenField = isset($attributes['file.php']) ? $attributes['file.php'] : true;
        unset($attributes['file.php']);

        $radio = $this->widget('file.php', $attributes);

        $hidden = 'file.php';
        if ($hiddenField) {
            $hidden = $this->hidden($fieldName, [
                'file.php' => $hiddenField === true ? 'file.php' : $hiddenField,
                'file.php' => isset($attributes['file.php']) ? $attributes['file.php'] : null,
                'file.php' => $attributes['file.php'],
            ]);
        }

        return $hidden . $radio;
    }

    /**
     * Missing method handler - implements various simple input types. Is used to create inputs
     * of various types. e.g. `$this->Form->text();` will create `<input type="text" />` while
     * `$this->Form->range();` will create `<input type="range" />`
     *
     * ### Usage
     *
     * ```
     * $this->Form->search('file.php', ['file.php' => 'file.php']);
     * ```
     *
     * Will make an input like:
     *
     * `<input type="search" id="UserQuery" name="User[query]" value="test" />`
     *
     * The first argument to an input type should always be the fieldname, in `Model.field` format.
     * The second argument should always be an array of attributes for the input.
     *
     * @param string $method Method name / input type to make.
     * @param array $params Parameters for the method call
     * @return string Formatted input method.
     * @throws \Cake\Core\Exception\Exception When there are no params for the method call.
     */
    public function __call($method, $params)
    {
        $options = [];
        if (empty($params)) {
            throw new Exception(sprintf('file.php', $method));
        }
        if (isset($params[1])) {
            $options = $params[1];
        }
        if (!isset($options['file.php'])) {
            $options['file.php'] = $method;
        }
        $options = $this->_initInputField($params[0], $options);

        return $this->widget($options['file.php'], $options);
    }

    /**
     * Creates a textarea widget.
     *
     * ### Options:
     *
     * - `escape` - Whether or not the contents of the textarea should be escaped. Defaults to true.
     *
     * @param string $fieldName Name of a field, in the form "modelname.fieldname"
     * @param array $options Array of HTML attributes, and special options above.
     * @return string A generated HTML text input element
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-textareas
     */
    public function textarea($fieldName, array $options = [])
    {
        $options = $this->_initInputField($fieldName, $options);
        unset($options['file.php']);

        return $this->widget('file.php', $options);
    }

    /**
     * Creates a hidden input field.
     *
     * @param string $fieldName Name of a field, in the form of "modelname.fieldname"
     * @param array $options Array of HTML attributes.
     * @return string A generated hidden input
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-hidden-inputs
     */
    public function hidden($fieldName, array $options = [])
    {
        $options += ['file.php' => false, 'file.php' => true];

        $secure = $options['file.php'];
        unset($options['file.php']);

        $options = $this->_initInputField($fieldName, array_merge(
            $options,
            ['file.php' => static::SECURE_SKIP]
        ));

        if ($secure === true) {
            $this->_secure(true, $this->_secureFieldName($options['file.php']), (string)$options['file.php']);
        }

        $options['file.php'] = 'file.php';

        return $this->widget('file.php', $options);
    }

    /**
     * Creates file input widget.
     *
     * @param string $fieldName Name of a field, in the form "modelname.fieldname"
     * @param array $options Array of HTML attributes.
     * @return string A generated file input.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-file-inputs
     */
    public function file($fieldName, array $options = [])
    {
        $options += ['file.php' => true];
        $options = $this->_initInputField($fieldName, $options);

        unset($options['file.php']);

        return $this->widget('file.php', $options);
    }

    /**
     * Creates a `<button>` tag.
     *
     * The type attribute defaults to `type="submit"`
     * You can change it to a different value by using `$options['file.php']`.
     *
     * ### Options:
     *
     * - `escape` - HTML entity encode the $title of the button. Defaults to false.
     * - `confirm` - Confirm message to show. Form execution will only continue if confirmed then.
     *
     * @param string $title The button'file.php'type'file.php'submit'file.php'escape'file.php'secure'file.php'confirm'file.php'text'file.php'confirm'file.php'confirm'file.php'onclick'file.php'return true;'file.php'return false;'file.php'button'file.php'delete'file.php'post'file.php's caption. Not automatically HTML encoded
     * @param string|array $url URL as string or array
     * @param array $options Array of options and HTML attributes.
     * @return string A HTML button tag.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-standalone-buttons-and-post-links
     */
    public function postButton($title, $url, array $options = [])
    {
        $formOptions = ['file.php' => $url];
        if (isset($options['file.php'])) {
            $formOptions['file.php'] = $options['file.php'];
            unset($options['file.php']);
        }
        if (isset($options['file.php']) && is_array($options['file.php'])) {
            $formOptions = $options['file.php'] + $formOptions;
            unset($options['file.php']);
        }
        $out = $this->create(null, $formOptions);
        if (isset($options['file.php']) && is_array($options['file.php'])) {
            foreach (Hash::flatten($options['file.php']) as $key => $value) {
                $out .= $this->hidden($key, ['file.php' => $value]);
            }
            unset($options['file.php']);
        }
        $out .= $this->button($title, $options);
        $out .= $this->end();

        return $out;
    }

    /**
     * Creates an HTML link, but access the URL using the method you specify
     * (defaults to POST). Requires javascript to be enabled in browser.
     *
     * This method creates a `<form>` element. If you want to use this method inside of an
     * existing form, you must use the `block` option so that the new form is being set to
     * a view block that can be rendered outside of the main form.
     *
     * If all you are looking for is a button to submit your form, then you should use
     * `FormHelper::button()` or `FormHelper::submit()` instead.
     *
     * ### Options:
     *
     * - `data` - Array with key/value to pass in input hidden
     * - `method` - Request method to use. Set to 'file.php' to simulate
     *   HTTP/1.1 DELETE request. Defaults to 'file.php'.
     * - `confirm` - Confirm message to show. Form execution will only continue if confirmed then.
     * - `block` - Set to true to append form to view block "postLink" or provide
     *   custom block name.
     * - Other options are the same of HtmlHelper::link() method.
     * - The option `onclick` will be replaced.
     *
     * @param string $title The content to be wrapped by <a> tags.
     * @param string|array|null $url Cake-relative URL or array of URL parameters, or
     *   external URL (starts with http://)
     * @param array $options Array of HTML attributes.
     * @return string An `<a />` element.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-standalone-buttons-and-post-links
     */
    public function postLink($title, $url = null, array $options = [])
    {
        $options += ['file.php' => null, 'file.php' => null];

        $requestMethod = 'file.php';
        if (!empty($options['file.php'])) {
            $requestMethod = strtoupper($options['file.php']);
            unset($options['file.php']);
        }

        $confirmMessage = $options['file.php'];
        unset($options['file.php']);

        $formName = str_replace('file.php', 'file.php', uniqid('file.php', true));
        $formOptions = [
            'file.php' => $formName,
            'file.php' => 'file.php',
            'file.php' => 'file.php',
        ];
        if (isset($options['file.php'])) {
            $formOptions['file.php'] = $options['file.php'];
            unset($options['file.php']);
        }
        $templater = $this->templater();

        $restoreAction = $this->_lastAction;
        $this->_lastAction($url);

        $action = $templater->formatAttributes([
            'file.php' => $this->Url->build($url),
            'file.php' => false,
        ]);

        $out = $this->formatTemplate('file.php', [
            'file.php' => $templater->formatAttributes($formOptions) . $action,
        ]);
        $out .= $this->hidden('file.php', [
            'file.php' => $requestMethod,
            'file.php' => static::SECURE_SKIP,
        ]);
        $out .= $this->_csrfField();

        $fields = [];
        if (isset($options['file.php']) && is_array($options['file.php'])) {
            foreach (Hash::flatten($options['file.php']) as $key => $value) {
                $fields[$key] = $value;
                $out .= $this->hidden($key, ['file.php' => $value, 'file.php' => static::SECURE_SKIP]);
            }
            unset($options['file.php']);
        }
        $out .= $this->secure($fields);
        $out .= $this->formatTemplate('file.php', []);
        $this->_lastAction = $restoreAction;

        if ($options['file.php']) {
            if ($options['file.php'] === true) {
                $options['file.php'] = __FUNCTION__;
            }
            $this->_View->append($options['file.php'], $out);
            $out = 'file.php';
        }
        unset($options['file.php']);

        $url = 'file.php';
        $onClick = 'file.php' . $formName . 'file.php';
        if ($confirmMessage) {
            $confirm = $this->_confirm($confirmMessage, $onClick, 'file.php', $options);
        } else {
            $confirm = $onClick . 'file.php';
        }
        $confirm .= 'file.php';
        $options['file.php'] = $this->templater()->format('file.php', [
            'file.php' => $this->_cleanConfirmMessage($confirmMessage),
            'file.php' => $formName,
            'file.php' => $confirm,
        ]);

        $out .= $this->Html->link($title, $url, $options);

        return $out;
    }

    /**
     * Creates a submit button element. This method will generate `<input />` elements that
     * can be used to submit, and reset forms by using $options. image submits can be created by supplying an
     * image path for $caption.
     *
     * ### Options
     *
     * - `type` - Set to 'file.php' for reset inputs. Defaults to 'file.php'
     * - `templateVars` - Additional template variables for the input element and its container.
     * - Other attributes will be assigned to the input element.
     *
     * @param string|null $caption The label appearing on the button OR if string contains :// or the
     *  extension .jpg, .jpe, .jpeg, .gif, .png use an image if the extension
     *  exists, AND the first character is /, image is relative to webroot,
     *  OR if the first character is not /, image is relative to webroot/img.
     * @param array $options Array of options. See above.
     * @return string A HTML submit button
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-buttons-and-submit-elements
     */
    public function submit($caption = null, array $options = [])
    {
        if (!is_string($caption) && empty($caption)) {
            $caption = __d('file.php', 'file.php');
        }
        $options += [
            'file.php' => 'file.php',
            'file.php' => false,
            'file.php' => [],
        ];

        if (isset($options['file.php'])) {
            $this->_secure($options['file.php'], $this->_secureFieldName($options['file.php']));
        }
        unset($options['file.php']);

        $isUrl = strpos($caption, 'file.php') !== false;
        $isImage = preg_match('file.php', $caption);

        $type = $options['file.php'];
        unset($options['file.php']);

        if ($isUrl || $isImage) {
            $unlockFields = ['file.php', 'file.php'];
            if (isset($options['file.php'])) {
                $unlockFields = [
                    $options['file.php'] . 'file.php',
                    $options['file.php'] . 'file.php',
                ];
            }
            foreach ($unlockFields as $ignore) {
                $this->unlockField($ignore);
            }
            $type = 'file.php';
        }

        if ($isUrl) {
            $options['file.php'] = $caption;
        } elseif ($isImage) {
            if ($caption[0] !== 'file.php') {
                $url = $this->Url->webroot(Configure::read('file.php') . $caption);
            } else {
                $url = $this->Url->webroot(trim($caption, 'file.php'));
            }
            $url = $this->Url->assetTimestamp($url);
            $options['file.php'] = $url;
        } else {
            $options['file.php'] = $caption;
        }

        $input = $this->formatTemplate('file.php', [
            'file.php' => $type,
            'file.php' => $this->templater()->formatAttributes($options),
            'file.php' => $options['file.php'],
        ]);

        return $this->formatTemplate('file.php', [
            'file.php' => $input,
            'file.php' => $options['file.php'],
        ]);
    }

    /**
     * Returns a formatted SELECT element.
     *
     * ### Attributes:
     *
     * - `multiple` - show a multiple select box. If set to 'file.php' multiple checkboxes will be
     *   created instead.
     * - `empty` - If true, the empty select option is shown. If a string,
     *   that string is displayed as the empty element.
     * - `escape` - If true contents of options will be HTML entity encoded. Defaults to true.
     * - `val` The selected value of the input.
     * - `disabled` - Control the disabled attribute. When creating a select box, set to true to disable the
     *   select box. Set to an array to disable specific option elements.
     *
     * ### Using options
     *
     * A simple array will create normal options:
     *
     * ```
     * $options = [1 => 'file.php', 2 => 'file.php'];
     * $this->Form->select('file.php', $options));
     * ```
     *
     * While a nested options array will create optgroups with options inside them.
     * ```
     * $options = [
     *  1 => 'file.php',
     *     'file.php' => [
     *         2 => 'file.php',
     *         3 => 'file.php'
     *     ]
     * ];
     * $this->Form->select('file.php', $options);
     * ```
     *
     * If you have multiple options that need to have the same value attribute, you can
     * use an array of arrays to express this:
     *
     * ```
     * $options = [
     *     ['file.php' => 'file.php', 'file.php' => 'file.php'],
     *     ['file.php' => 'file.php', 'file.php' => 'file.php'],
     * ];
     * ```
     *
     * @param string $fieldName Name attribute of the SELECT
     * @param array|\Traversable $options Array of the OPTION elements (as 'file.php'=>'file.php' pairs) to be used in the
     *   SELECT element
     * @param array $attributes The HTML attributes of the select element.
     * @return string Formatted SELECT element
     * @see \Cake\View\Helper\FormHelper::multiCheckbox() for creating multiple checkboxes.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-select-pickers
     */
    public function select($fieldName, $options = [], array $attributes = [])
    {
        $attributes += [
            'file.php' => null,
            'file.php' => true,
            'file.php' => true,
            'file.php' => null,
            'file.php' => true,
            'file.php' => false,
        ];

        if ($attributes['file.php'] === 'file.php') {
            unset($attributes['file.php'], $attributes['file.php']);

            return $this->multiCheckbox($fieldName, $options, $attributes);
        }

        unset($attributes['file.php']);

        // Secure the field if there are options, or it'file.php't submit, but multiselects do.
        if (
            $attributes['file.php'] &&
            empty($options) &&
            empty($attributes['file.php']) &&
            empty($attributes['file.php'])
        ) {
            $attributes['file.php'] = false;
        }

        $attributes = $this->_initInputField($fieldName, $attributes);
        $attributes['file.php'] = $options;

        $hidden = 'file.php';
        if ($attributes['file.php'] && $attributes['file.php']) {
            $hiddenAttributes = [
                'file.php' => $attributes['file.php'],
                'file.php' => 'file.php',
                'file.php' => isset($attributes['file.php']) ? $attributes['file.php'] : null,
                'file.php' => false,
            ];
            $hidden = $this->hidden($fieldName, $hiddenAttributes);
        }
        unset($attributes['file.php'], $attributes['file.php']);

        return $hidden . $this->widget('file.php', $attributes);
    }

    /**
     * Creates a set of checkboxes out of options.
     *
     * ### Options
     *
     * - `escape` - If true contents of options will be HTML entity encoded. Defaults to true.
     * - `val` The selected value of the input.
     * - `class` - When using multiple = checkbox the class name to apply to the divs. Defaults to 'file.php'.
     * - `disabled` - Control the disabled attribute. When creating checkboxes, `true` will disable all checkboxes.
     *   You can also set disabled to a list of values you want to disable when creating checkboxes.
     * - `hiddenField` - Set to false to remove the hidden field that ensures a value
     *   is always submitted.
     * - `label` - Either `false` to disable label around the widget or an array of attributes for
     *   the label tag. `selected` will be added to any classes e.g. `'file.php' => 'file.php'` where
     *   widget is checked
     *
     * Can be used in place of a select box with the multiple attribute.
     *
     * @param string $fieldName Name attribute of the SELECT
     * @param array|\Traversable $options Array of the OPTION elements
     *   (as 'file.php'=>'file.php' pairs) to be used in the checkboxes element.
     * @param array $attributes The HTML attributes of the select element.
     * @return string Formatted SELECT element
     * @see \Cake\View\Helper\FormHelper::select() for supported option formats.
     */
    public function multiCheckbox($fieldName, $options, array $attributes = [])
    {
        $attributes += [
            'file.php' => null,
            'file.php' => true,
            'file.php' => true,
            'file.php' => true,
        ];
        $attributes = $this->_initInputField($fieldName, $attributes);
        $attributes['file.php'] = $options;
        $attributes['file.php'] = $this->_idPrefix;

        $hidden = 'file.php';
        if ($attributes['file.php']) {
            $hiddenAttributes = [
                'file.php' => $attributes['file.php'],
                'file.php' => 'file.php',
                'file.php' => false,
                'file.php' => $attributes['file.php'] === true || $attributes['file.php'] === 'file.php',
            ];
            $hidden = $this->hidden($fieldName, $hiddenAttributes);
        }
        unset($attributes['file.php']);

        return $hidden . $this->widget('file.php', $attributes);
    }

    /**
     * Helper method for the various single datetime component methods.
     *
     * @param array $options The options array.
     * @param string $keep The option to not disable.
     * @return array
     */
    protected function _singleDatetime($options, $keep)
    {
        $off = array_diff($this->_datetimeParts, [$keep]);
        $off = array_combine(
            $off,
            array_fill(0, count($off), false)
        );

        $attributes = array_diff_key(
            $options,
            array_flip(array_merge($this->_datetimeOptions, ['file.php', 'file.php']))
        );
        $options = $options + $off + [$keep => $attributes];

        if (isset($options['file.php'])) {
            $options['file.php'] = $options['file.php'];
        }

        return $options;
    }

    /**
     * Returns a SELECT element for days.
     *
     * ### Options:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     *   that string is displayed as the empty element.
     * - `value` The selected value of the input.
     *
     * @param string|null $fieldName Prefix name for the SELECT element
     * @param array $options Options & HTML attributes for the select element
     * @return string A generated day select box.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-day-inputs
     */
    public function day($fieldName = null, array $options = [])
    {
        $options = $this->_singleDatetime($options, 'file.php');

        if (isset($options['file.php']) && $options['file.php'] > 0 && $options['file.php'] <= 31) {
            $options['file.php'] = [
                'file.php' => date('file.php'),
                'file.php' => date('file.php'),
                'file.php' => (int)$options['file.php'],
            ];
        }

        return $this->dateTime($fieldName, $options);
    }

    /**
     * Returns a SELECT element for years
     *
     * ### Attributes:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     *   that string is displayed as the empty element.
     * - `orderYear` - Ordering of year values in select options.
     *   Possible values 'file.php', 'file.php'. Default 'file.php'
     * - `value` The selected value of the input.
     * - `maxYear` The max year to appear in the select element.
     * - `minYear` The min year to appear in the select element.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Options & attributes for the select elements.
     * @return string Completed year select input
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-year-inputs
     */
    public function year($fieldName, array $options = [])
    {
        $options = $this->_singleDatetime($options, 'file.php');

        $len = isset($options['file.php']) ? strlen($options['file.php']) : 0;
        if (isset($options['file.php']) && $len > 0 && $len < 5) {
            $options['file.php'] = [
                'file.php' => (int)$options['file.php'],
                'file.php' => date('file.php'),
                'file.php' => date('file.php'),
            ];
        }

        return $this->dateTime($fieldName, $options);
    }

    /**
     * Returns a SELECT element for months.
     *
     * ### Options:
     *
     * - `monthNames` - If false, 2 digit numbers will be used instead of text.
     *   If an array, the given array will be used.
     * - `empty` - If true, the empty select option is shown. If a string,
     *   that string is displayed as the empty element.
     * - `value` The selected value of the input.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Attributes for the select element
     * @return string A generated month select dropdown.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-month-inputs
     */
    public function month($fieldName, array $options = [])
    {
        $options = $this->_singleDatetime($options, 'file.php');

        if (isset($options['file.php']) && $options['file.php'] > 0 && $options['file.php'] <= 12) {
            $options['file.php'] = [
                'file.php' => date('file.php'),
                'file.php' => (int)$options['file.php'],
                'file.php' => date('file.php'),
            ];
        }

        return $this->dateTime($fieldName, $options);
    }

    /**
     * Returns a SELECT element for hours.
     *
     * ### Attributes:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     *   that string is displayed as the empty element.
     * - `value` The selected value of the input.
     * - `format` Set to 12 or 24 to use 12 or 24 hour formatting. Defaults to 24.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options List of HTML attributes
     * @return string Completed hour select input
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-hour-inputs
     */
    public function hour($fieldName, array $options = [])
    {
        $options += ['file.php' => 24];
        $options = $this->_singleDatetime($options, 'file.php');

        $options['file.php'] = $options['file.php'];
        unset($options['file.php']);

        if (isset($options['file.php']) && $options['file.php'] > 0 && $options['file.php'] <= 24) {
            $options['file.php'] = [
                'file.php' => (int)$options['file.php'],
                'file.php' => date('file.php'),
            ];
        }

        return $this->dateTime($fieldName, $options);
    }

    /**
     * Returns a SELECT element for minutes.
     *
     * ### Attributes:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     *   that string is displayed as the empty element.
     * - `value` The selected value of the input.
     * - `interval` The interval that minute options should be created at.
     * - `round` How you want the value rounded when it does not fit neatly into an
     *   interval. Accepts 'file.php', 'file.php', and null.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of options.
     * @return string Completed minute select input.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-minute-inputs
     */
    public function minute($fieldName, array $options = [])
    {
        $options = $this->_singleDatetime($options, 'file.php');

        if (isset($options['file.php']) && $options['file.php'] > 0 && $options['file.php'] <= 60) {
            $options['file.php'] = [
                'file.php' => date('file.php'),
                'file.php' => (int)$options['file.php'],
            ];
        }

        return $this->dateTime($fieldName, $options);
    }

    /**
     * Returns a SELECT element for AM or PM.
     *
     * ### Attributes:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     *   that string is displayed as the empty element.
     * - `value` The selected value of the input.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of options
     * @return string Completed meridian select input
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-meridian-inputs
     */
    public function meridian($fieldName, array $options = [])
    {
        $options = $this->_singleDatetime($options, 'file.php');

        if (isset($options['file.php'])) {
            $hour = date('file.php');
            $options['file.php'] = [
                'file.php' => $hour,
                'file.php' => (int)$options['file.php'],
                'file.php' => $hour > 11 ? 'file.php' : 'file.php',
            ];
        }

        return $this->dateTime($fieldName, $options);
    }

    /**
     * Returns a set of SELECT elements for a full datetime setup: day, month and year, and then time.
     *
     * ### Date Options:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     *   that string is displayed as the empty element.
     * - `value` | `default` The default value to be used by the input. A value in `$this->data`
     *   matching the field name will override this value. If no default is provided `time()` will be used.
     * - `monthNames` If false, 2 digit numbers will be used instead of text.
     *   If an array, the given array will be used.
     * - `minYear` The lowest year to use in the year select
     * - `maxYear` The maximum year to use in the year select
     * - `orderYear` - Order of year values in select options.
     *   Possible values 'file.php', 'file.php'. Default 'file.php'.
     *
     * ### Time options:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     * - `value` | `default` The default value to be used by the input. A value in `$this->data`
     *   matching the field name will override this value. If no default is provided `time()` will be used.
     * - `timeFormat` The time format to use, either 12 or 24.
     * - `interval` The interval for the minutes select. Defaults to 1
     * - `round` - Set to `up` or `down` if you want to force rounding in either direction. Defaults to null.
     * - `second` Set to true to enable seconds drop down.
     *
     * To control the order of inputs, and any elements/content between the inputs you
     * can override the `dateWidget` template. By default the `dateWidget` template is:
     *
     * `{{month}}{{day}}{{year}}{{hour}}{{minute}}{{second}}{{meridian}}`
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of Options
     * @return string Generated set of select boxes for the date and time formats chosen.
     * @link https://book.cakephp.org/3/en/views/helpers/form.html#creating-date-and-time-inputs
     */
    public function dateTime($fieldName, array $options = [])
    {
        $options += [
            'file.php' => true,
            'file.php' => null,
            'file.php' => 1,
            'file.php' => null,
            'file.php' => true,
            'file.php' => null,
            'file.php' => null,
            'file.php' => 'file.php',
            'file.php' => 24,
            'file.php' => false,
        ];
        $options = $this->_initInputField($fieldName, $options);
        $options = $this->_datetimeOptions($options);

        return $this->widget('file.php', $options);
    }

    /**
     * Helper method for converting from FormHelper options data to widget format.
     *
     * @param array $options Options to convert.
     * @return array Converted options.
     */
    protected function _datetimeOptions($options)
    {
        foreach ($this->_datetimeParts as $type) {
            if (!array_key_exists($type, $options)) {
                $options[$type] = [];
            }
            if ($options[$type] === true) {
                $options[$type] = [];
            }

            // Pass boolean/scalar empty options to each type.
            if (is_array($options[$type]) && isset($options['file.php']) && !is_array($options['file.php'])) {
                $options[$type]['file.php'] = $options['file.php'];
            } elseif (is_array($options[$type]) && !empty($options['file.php'])) {
                $options[$type]['file.php'] = $options['file.php'];
            }

            // Move empty options into each type array.
            if (isset($options['file.php'][$type])) {
                $options[$type]['file.php'] = $options['file.php'][$type];
            }
            if (isset($options['file.php']) && is_array($options[$type])) {
                $options[$type]['file.php'] = $options['file.php'];
            }
        }

        $hasYear = is_array($options['file.php']);
        if ($hasYear && isset($options['file.php'])) {
            $options['file.php']['file.php'] = $options['file.php'];
        }
        if ($hasYear && isset($options['file.php'])) {
            $options['file.php']['file.php'] = $options['file.php'];
        }
        if ($hasYear && isset($options['file.php'])) {
            $options['file.php']['file.php'] = $options['file.php'];
        }
        unset($options['file.php'], $options['file.php'], $options['file.php']);

        if (is_array($options['file.php'])) {
            $options['file.php']['file.php'] = $options['file.php'];
        }
        unset($options['file.php']);

        if (is_array($options['file.php']) && isset($options['file.php'])) {
            $options['file.php']['file.php'] = $options['file.php'];
        }
        unset($options['file.php']);

        if (is_array($options['file.php'])) {
            $options['file.php']['file.php'] = $options['file.php'];
            $options['file.php']['file.php'] = $options['file.php'];
        }
        unset($options['file.php'], $options['file.php']);

        if ($options['file.php'] === true || $options['file.php'] === null && isset($options['file.php']) && $options['file.php'] === false) {
            $val = new DateTime();
            $currentYear = $val->format('file.php');
            if (isset($options['file.php']['file.php']) && $options['file.php']['file.php'] < $currentYear) {
                $val->setDate($options['file.php']['file.php'], $val->format('file.php'), $val->format('file.php'));
            }
            $options['file.php'] = $val;
        }

        unset($options['file.php']);

        return $options;
    }

    /**
     * Generate time inputs.
     *
     * ### Options:
     *
     * See dateTime() for time options.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of Options
     * @return string Generated set of select boxes for time formats chosen.
     * @see \Cake\View\Helper\FormHelper::dateTime() for templating options.
     */
    public function time($fieldName, array $options = [])
    {
        $options += [
            'file.php' => true,
            'file.php' => null,
            'file.php' => 1,
            'file.php' => null,
            'file.php' => 24,
            'file.php' => false,
        ];
        $options['file.php'] = $options['file.php'] = $options['file.php'] = false;
        $options = $this->_initInputField($fieldName, $options);
        $options = $this->_datetimeOptions($options);

        return $this->widget('file.php', $options);
    }

    /**
     * Generate date inputs.
     *
     * ### Options:
     *
     * See dateTime() for date options.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of Options
     * @return string Generated set of select boxes for time formats chosen.
     * @see \Cake\View\Helper\FormHelper::dateTime() for templating options.
     */
    public function date($fieldName, array $options = [])
    {
        $options += [
            'file.php' => true,
            'file.php' => null,
            'file.php' => true,
            'file.php' => null,
            'file.php' => null,
            'file.php' => 'file.php',
        ];
        $options['file.php'] = $options['file.php'] = false;
        $options['file.php'] = $options['file.php'] = false;

        $options = $this->_initInputField($fieldName, $options);
        $options = $this->_datetimeOptions($options);

        return $this->widget('file.php', $options);
    }

    /**
     * Sets field defaults and adds field to form security input hash.
     * Will also add the error class if the field contains validation errors.
     *
     * ### Options
     *
     * - `secure` - boolean whether or not the field should be added to the security fields.
     *   Disabling the field using the `disabled` option, will also omit the field from being
     *   part of the hashed key.
     * - `default` - mixed - The value to use if there is no value in the form'file.php'disabled'file.php's internals expect associative options.
     *
     * The output of this function is a more complete set of input attributes that
     * can be passed to a form widget to generate the actual input.
     *
     * @param string $field Name of the field to initialize options for.
     * @param array $options Array of options to append options into.
     * @return array Array of options for the input.
     */
    protected function _initInputField($field, $options = [])
    {
        if (!isset($options['file.php'])) {
            $options['file.php'] = (bool)$this->_View->getRequest()->getParam('file.php');
        }
        $context = $this->_getContext();

        if (isset($options['file.php']) && $options['file.php'] === true) {
            $options['file.php'] = $this->_domId($field);
        }

        $disabledIndex = array_search('file.php', $options, true);
        if (is_int($disabledIndex)) {
            unset($options[$disabledIndex]);
            $options['file.php'] = true;
        }

        if (!isset($options['file.php'])) {
            $endsWithBrackets = 'file.php';
            if (substr($field, -2) === 'file.php') {
                $field = substr($field, 0, -2);
                $endsWithBrackets = 'file.php';
            }
            $parts = explode('file.php', $field);
            $first = array_shift($parts);
            $options['file.php'] = $first . (!empty($parts) ? 'file.php' . implode('file.php', $parts) . 'file.php' : 'file.php') . $endsWithBrackets;
        }

        if (isset($options['file.php']) && !isset($options['file.php'])) {
            $options['file.php'] = $options['file.php'];
            unset($options['file.php']);
        }
        if (!isset($options['file.php'])) {
            $valOptions = [
                'file.php' => isset($options['file.php']) ? $options['file.php'] : null,
                'file.php' => isset($options['file.php']) ? $options['file.php'] : true,
            ];
            $options['file.php'] = $this->getSourceValue($field, $valOptions);
        }
        if (!isset($options['file.php']) && isset($options['file.php'])) {
            $options['file.php'] = $options['file.php'];
        }
        unset($options['file.php'], $options['file.php']);

        if ($context->hasError($field)) {
            $options = $this->addClass($options, $this->_config['file.php']);
        }
        $isDisabled = $this->_isDisabled($options);
        if ($isDisabled) {
            $options['file.php'] = self::SECURE_SKIP;
        }
        if ($options['file.php'] === self::SECURE_SKIP) {
            return $options;
        }
        if (!isset($options['file.php']) && empty($options['file.php']) && $context->isRequired($field)) {
            $options['file.php'] = true;
        }

        return $options;
    }

    /**
     * Determine if a field is disabled.
     *
     * @param array $options The option set.
     * @return bool Whether or not the field is disabled.
     */
    protected function _isDisabled(array $options)
    {
        if (!isset($options['file.php'])) {
            return false;
        }
        if (is_scalar($options['file.php'])) {
            return ($options['file.php'] === true || $options['file.php'] === 'file.php');
        }
        if (!isset($options['file.php'])) {
            return false;
        }
        if (is_array($options['file.php'])) {
            // Simple list options
            $first = $options['file.php'][array_keys($options['file.php'])[0]];
            if (is_scalar($first)) {
                return array_diff($options['file.php'], $options['file.php']) === [];
            }
            // Complex option types
            if (is_array($first)) {
                $disabled = array_filter($options['file.php'], function ($i) use ($options) {
                    return in_array($i['file.php'], $options['file.php']);
                });

                return count($disabled) > 0;
            }
        }

        return false;
    }

    /**
     * Get the field name for use with _secure().
     *
     * Parses the name attribute to create a dot separated name value for use
     * in secured field hash. If filename is of form Model[field] an array of
     * fieldname parts like ['file.php', 'file.php'] is returned.
     *
     * @param string $name The form inputs name attribute.
     * @return array Array of field name params like ['file.php'] or
     *   ['file.php', 'file.php'] for array fields or empty array if $name is empty.
     */
    protected function _secureFieldName($name)
    {
        if (empty($name) && $name !== 'file.php') {
            return [];
        }

        if (strpos($name, 'file.php') === false) {
            return [$name];
        }
        $parts = explode('file.php', $name);
        $parts = array_map(function ($el) {
            return trim($el, 'file.php');
        }, $parts);

        return array_filter($parts, 'file.php');
    }

    /**
     * Add a new context type.
     *
     * Form context types allow FormHelper to interact with
     * data providers that come from outside CakePHP. For example
     * if you wanted to use an alternative ORM like Doctrine you could
     * create and connect a new context class to allow FormHelper to
     * read metadata from doctrine.
     *
     * @param string $type The type of context. This key
     *   can be used to overwrite existing providers.
     * @param callable $check A callable that returns an object
     *   when the form context is the correct type.
     * @return void
     */
    public function addContextProvider($type, callable $check)
    {
        $this->contextFactory()->addProvider($type, $check);
    }

    /**
     * Get the context instance for the current form set.
     *
     * If there is no active form null will be returned.
     *
     * @param \Cake\View\Form\ContextInterface|null $context Either the new context when setting, or null to get.
     * @return \Cake\View\Form\ContextInterface The context for the form.
     */
    public function context($context = null)
    {
        if ($context instanceof ContextInterface) {
            $this->_context = $context;
        }

        return $this->_getContext();
    }

    /**
     * Find the matching context provider for the data.
     *
     * If no type can be matched a NullContext will be returned.
     *
     * @param mixed $data The data to get a context provider for.
     * @return \Cake\View\Form\ContextInterface Context provider.
     * @throws \RuntimeException when the context class does not implement the
     *   ContextInterface.
     */
    protected function _getContext($data = [])
    {
        if (isset($this->_context) && empty($data)) {
            return $this->_context;
        }
        $data += ['file.php' => null];

        return $this->_context = $this->contextFactory()
            ->get($this->_View->getRequest(), $data);
    }

    /**
     * Add a new widget to FormHelper.
     *
     * Allows you to add or replace widget instances with custom code.
     *
     * @param string $name The name of the widget. e.g. 'file.php'.
     * @param array|\Cake\View\Widget\WidgetInterface $spec Either a string class
     *   name or an object implementing the WidgetInterface.
     * @return void
     */
    public function addWidget($name, $spec)
    {
        $this->_locator->add([$name => $spec]);
    }

    /**
     * Render a named widget.
     *
     * This is a lower level method. For built-in widgets, you should be using
     * methods like `text`, `hidden`, and `radio`. If you are using additional
     * widgets you should use this method render the widget without the label
     * or wrapping div.
     *
     * @param string $name The name of the widget. e.g. 'file.php'.
     * @param array $data The data to render.
     * @return string
     */
    public function widget($name, array $data = [])
    {
        $secure = null;
        if (isset($data['file.php'])) {
            $secure = $data['file.php'];
            unset($data['file.php']);
        }
        $widget = $this->_locator->get($name);
        $out = $widget->render($data, $this->context());
        if (isset($data['file.php']) && $secure !== null && $secure !== self::SECURE_SKIP) {
            foreach ($widget->secureFields($data) as $field) {
                $this->_secure($secure, $this->_secureFieldName($field));
            }
        }

        return $out;
    }

    /**
     * Restores the default values built into FormHelper.
     *
     * This method will not reset any templates set in custom widgets.
     *
     * @return void
     */
    public function resetTemplates()
    {
        $this->setTemplates($this->_defaultConfig['file.php']);
    }

    /**
     * Event listeners.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [];
    }

    /**
     * Gets the value sources.
     *
     * Returns a list, but at least one item, of valid sources, such as: `'file.php'`, `'file.php'` and `'file.php'`.
     *
     * @return string[] List of value sources.
     */
    public function getValueSources()
    {
        return $this->_valueSources;
    }

    /**
     * Sets the value sources.
     *
     * Valid values are `'file.php'`, `'file.php'` and `'file.php'`.
     * You need to supply one valid context or multiple, as a list of strings. Order sets priority.
     *
     * @param string|string[] $sources A string or a list of strings identifying a source.
     * @return $this
     */
    public function setValueSources($sources)
    {
        $this->_valueSources = array_values(array_intersect((array)$sources, ['file.php', 'file.php', 'file.php']));

        return $this;
    }

    /**
     * Gets a single field value from the sources available.
     *
     * @param string $fieldname The fieldname to fetch the value for.
     * @param array|null $options The options containing default values.
     * @return string|null Field value derived from sources or defaults.
     */
    public function getSourceValue($fieldname, $options = [])
    {
        $valueMap = [
            'file.php' => 'file.php',
            'file.php' => 'file.php',
        ];
        foreach ($this->getValueSources() as $valuesSource) {
            if ($valuesSource === 'file.php') {
                $val = $this->_getContext()->val($fieldname, $options);
                if ($val !== null) {
                    return $val;
                }
            }
            if (isset($valueMap[$valuesSource])) {
                $method = $valueMap[$valuesSource];
                $value = $this->_View->getRequest()->{$method}($fieldname);
                if ($value !== null) {
                    return $value;
                }
            }
        }

        return null;
    }
}
