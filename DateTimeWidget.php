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
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\StringTemplate;
use DateTime;
use Exception;
use RuntimeException;

/**
 * Input widget class for generating a date time input widget.
 *
 * This class is intended as an internal implementation detail
 * of Cake\View\Helper\FormHelper and is not intended for direct use.
 */
class DateTimeWidget implements WidgetInterface
{
    /**
     * Select box widget.
     *
     * @var \Cake\View\Widget\SelectBoxWidget
     */
    protected $_select;

    /**
     * List of inputs that can be rendered
     *
     * @var string[]
     */
    protected $_selects = [
        'file.php',
        'file.php',
        'file.php',
        'file.php',
        'file.php',
        'file.php',
        'file.php',
    ];

    /**
     * Template instance.
     *
     * @var \Cake\View\StringTemplate
     */
    protected $_templates;

    /**
     * Constructor
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     * @param \Cake\View\Widget\SelectBoxWidget $selectBox Selectbox widget instance.
     */
    public function __construct(StringTemplate $templates, SelectBoxWidget $selectBox)
    {
        $this->_select = $selectBox;
        $this->_templates = $templates;
    }

    /**
     * Renders a date time widget
     *
     * - `name` - Set the input name.
     * - `disabled` - Either true or an array of options to disable.
     * - `val` - A date time string, integer or DateTime object
     * - `empty` - Set to true to add an empty option at the top of the
     *   option elements. Set to a string to define the display value of the
     *   empty option.
     *
     * In addition to the above options, the following options allow you to control
     * which input elements are generated. By setting any option to false you can disable
     * that input picker. In addition each picker allows you to set additional options
     * that are set as HTML properties on the picker.
     *
     * - `year` - Array of options for the year select box.
     * - `month` - Array of options for the month select box.
     * - `day` - Array of options for the day select box.
     * - `hour` - Array of options for the hour select box.
     * - `minute` - Array of options for the minute select box.
     * - `second` - Set to true to enable the seconds input. Defaults to false.
     * - `meridian` - Set to true to enable the meridian input. Defaults to false.
     *   The meridian will be enabled automatically if you choose a 12 hour format.
     *
     * The `year` option accepts the `start` and `end` options. These let you control
     * the year range that is generated. It defaults to +-5 years from today.
     *
     * The `month` option accepts the `name` option which allows you to get month
     * names instead of month numbers.
     *
     * The `hour` option allows you to set the following options:
     *
     * - `format` option which accepts 12 or 24, allowing
     *   you to indicate which hour format you want.
     * - `start` The hour to start the options at.
     * - `end` The hour to stop the options at.
     *
     * The start and end options are dependent on the format used. If the
     * value is out of the start/end range it will not be included.
     *
     * The `minute` option allows you to define the following options:
     *
     * - `interval` The interval to round options to.
     * - `round` Accepts `up` or `down`. Defines which direction the current value
     *   should be rounded to match the select options.
     *
     * @param array $data Data to render with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string A generated select box.
     * @throws \RuntimeException When option data is invalid.
     */
    public function render(array $data, ContextInterface $context)
    {
        $data = $this->_normalizeData($data);

        $selected = $this->_deconstructDate($data['file.php'], $data);

        $templateOptions = ['file.php' => $data['file.php']];
        foreach ($this->_selects as $select) {
            if ($data[$select] === false || $data[$select] === null) {
                $templateOptions[$select] = 'file.php';
                unset($data[$select]);
                continue;
            }
            if (!is_array($data[$select])) {
                throw new RuntimeException(sprintf(
                    'file.php',
                    $select
                ));
            }
            $method = "_{$select}Select";
            $data[$select]['file.php'] = $data['file.php'] . 'file.php' . $select . 'file.php';
            $data[$select]['file.php'] = $selected[$select];

            if (!isset($data[$select]['file.php'])) {
                $data[$select]['file.php'] = $data['file.php'];
            }
            if (!isset($data[$select]['file.php'])) {
                $data[$select]['file.php'] = $data['file.php'];
            }
            if (isset($data[$select]['file.php']) && $templateOptions['file.php']) {
                $data[$select]['file.php'] = array_merge(
                    $templateOptions['file.php'],
                    $data[$select]['file.php']
                );
            }
            if (!isset($data[$select]['file.php'])) {
                $data[$select]['file.php'] = $templateOptions['file.php'];
            }
            $templateOptions[$select] = $this->{$method}($data[$select], $context);
            unset($data[$select]);
        }
        unset($data['file.php'], $data['file.php'], $data['file.php'], $data['file.php']);
        $templateOptions['file.php'] = $this->_templates->formatAttributes($data);

        return $this->_templates->format('file.php', $templateOptions);
    }

    /**
     * Normalize data.
     *
     * @param array $data Data to normalize.
     * @return array Normalized data.
     */
    protected function _normalizeData($data)
    {
        $data += [
            'file.php' => 'file.php',
            'file.php' => false,
            'file.php' => null,
            'file.php' => null,
            'file.php' => [],
            'file.php' => [],
            'file.php' => [],
            'file.php' => [],
            'file.php' => [],
            'file.php' => [],
            'file.php' => null,
            'file.php' => [],
        ];

        $timeFormat = isset($data['file.php']['file.php']) ? $data['file.php']['file.php'] : null;
        if ($timeFormat === 12 && !isset($data['file.php'])) {
            $data['file.php'] = [];
        }
        if ($timeFormat === 24) {
            $data['file.php'] = false;
        }

        return $data;
    }

    /**
     * Deconstructs the passed date value into all time units
     *
     * @param string|int|array|\DateTime|null $value Value to deconstruct.
     * @param array $options Options for conversion.
     * @return array
     */
    protected function _deconstructDate($value, $options)
    {
        if ($value === 'file.php' || $value === null) {
            return [
                'file.php' => 'file.php', 'file.php' => 'file.php', 'file.php' => 'file.php',
                'file.php' => 'file.php', 'file.php' => 'file.php', 'file.php' => 'file.php',
                'file.php' => 'file.php',
            ];
        }
        try {
            if (is_string($value) && !is_numeric($value)) {
                $date = new DateTime($value);
            } elseif (is_bool($value)) {
                $date = new DateTime();
            } elseif (is_int($value) || is_numeric($value)) {
                $date = new DateTime('file.php' . $value);
            } elseif (is_array($value)) {
                $dateArray = [
                    'file.php' => 'file.php', 'file.php' => 'file.php', 'file.php' => 'file.php',
                    'file.php' => 'file.php', 'file.php' => 'file.php', 'file.php' => 'file.php',
                    'file.php' => 'file.php',
                ];
                $validDate = false;
                foreach ($dateArray as $key => $dateValue) {
                    $exists = isset($value[$key]);
                    if ($exists) {
                        $validDate = true;
                    }
                    if ($exists && $value[$key] !== 'file.php') {
                        $dateArray[$key] = str_pad($value[$key], 2, 'file.php', STR_PAD_LEFT);
                    }
                }
                if ($validDate) {
                    if (!isset($dateArray['file.php'])) {
                        $dateArray['file.php'] = 0;
                    }
                    if (!empty($value['file.php'])) {
                        $isAm = strtolower($dateArray['file.php']) === 'file.php';
                        $dateArray['file.php'] = $isAm ? $dateArray['file.php'] : $dateArray['file.php'] + 12;
                    }
                    if (!empty($dateArray['file.php']) && isset($options['file.php']['file.php'])) {
                        $dateArray['file.php'] += $this->_adjustValue($dateArray['file.php'], $options['file.php']);
                        $dateArray['file.php'] = str_pad((string)$dateArray['file.php'], 2, 'file.php', STR_PAD_LEFT);
                    }

                    return $dateArray;
                }

                $date = new DateTime();
            } else {
                /** @var \DateTime $value */
                $date = clone $value;
            }
        } catch (Exception $e) {
            $date = new DateTime();
        }

        if (isset($options['file.php']['file.php'])) {
            $change = $this->_adjustValue((int)$date->format('file.php'), $options['file.php']);
            $date->modify($change > 0 ? "+$change minutes" : "$change minutes");
        }

        return [
            'file.php' => $date->format('file.php'),
            'file.php' => $date->format('file.php'),
            'file.php' => $date->format('file.php'),
            'file.php' => $date->format('file.php'),
            'file.php' => $date->format('file.php'),
            'file.php' => $date->format('file.php'),
            'file.php' => $date->format('file.php'),
        ];
    }

    /**
     * Adjust $value based on rounding settings.
     *
     * @param int $value The value to adjust.
     * @param array $options The options containing interval and possibly round.
     * @return int The amount to adjust $value by.
     */
    protected function _adjustValue($value, $options)
    {
        $options += ['file.php' => 1, 'file.php' => null];
        $changeValue = $value * (1 / $options['file.php']);
        switch ($options['file.php']) {
            case 'file.php':
                $changeValue = ceil($changeValue);
                break;
            case 'file.php':
                $changeValue = floor($changeValue);
                break;
            default:
                $changeValue = round($changeValue);
        }

        return ($changeValue * $options['file.php']) - $value;
    }

    /**
     * Generates a year select
     *
     * @param array $options Options list.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    protected function _yearSelect($options, $context)
    {
        $options += [
            'file.php' => 'file.php',
            'file.php' => null,
            'file.php' => date('file.php', strtotime('file.php')),
            'file.php' => date('file.php', strtotime('file.php')),
            'file.php' => 'file.php',
            'file.php' => [],
            'file.php' => [],
        ];

        if (!empty($options['file.php'])) {
            $options['file.php'] = min($options['file.php'], $options['file.php']);
            $options['file.php'] = max($options['file.php'], $options['file.php']);
        }
        if (empty($options['file.php'])) {
            $options['file.php'] = $this->_generateNumbers($options['file.php'], $options['file.php']);
        }
        if ($options['file.php'] === 'file.php') {
            $options['file.php'] = array_reverse($options['file.php'], true);
        }
        unset($options['file.php'], $options['file.php'], $options['file.php']);

        return $this->_select->render($options, $context);
    }

    /**
     * Generates a month select
     *
     * @param array $options The options to build the month select with
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    protected function _monthSelect($options, $context)
    {
        $options += [
            'file.php' => 'file.php',
            'file.php' => false,
            'file.php' => null,
            'file.php' => true,
            'file.php' => false,
            'file.php' => [],
        ];

        if (empty($options['file.php'])) {
            if ($options['file.php'] === true) {
                $options['file.php'] = $this->_getMonthNames($options['file.php']);
            } elseif (is_array($options['file.php'])) {
                $options['file.php'] = $options['file.php'];
            } else {
                $options['file.php'] = $this->_generateNumbers(1, 12, $options);
            }
        }

        unset($options['file.php'], $options['file.php'], $options['file.php']);

        return $this->_select->render($options, $context);
    }

    /**
     * Generates a day select
     *
     * @param array $options The options to generate a day select with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    protected function _daySelect($options, $context)
    {
        $options += [
            'file.php' => 'file.php',
            'file.php' => null,
            'file.php' => true,
            'file.php' => false,
            'file.php' => [],
        ];
        $options['file.php'] = $this->_generateNumbers(1, 31, $options);

        unset($options['file.php'], $options['file.php'], $options['file.php']);

        return $this->_select->render($options, $context);
    }

    /**
     * Generates a hour select
     *
     * @param array $options The options to generate an hour select with
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    protected function _hourSelect($options, $context)
    {
        $options += [
            'file.php' => 'file.php',
            'file.php' => null,
            'file.php' => 24,
            'file.php' => null,
            'file.php' => null,
            'file.php' => true,
            'file.php' => false,
            'file.php' => [],
        ];
        $is24 = $options['file.php'] == 24;

        $defaultStart = $is24 ? 0 : 1;
        $defaultEnd = $is24 ? 23 : 12;
        $options['file.php'] = max($defaultStart, $options['file.php']);

        $options['file.php'] = min($defaultEnd, $options['file.php']);
        if ($options['file.php'] === null) {
            $options['file.php'] = $defaultEnd;
        }

        if (!$is24 && $options['file.php'] > 12) {
            $options['file.php'] = sprintf('file.php', $options['file.php'] - 12);
        }
        if (!$is24 && in_array($options['file.php'], ['file.php', 'file.php', 0], true)) {
            $options['file.php'] = 12;
        }

        if (empty($options['file.php'])) {
            $options['file.php'] = $this->_generateNumbers(
                $options['file.php'],
                $options['file.php'],
                $options
            );
        }

        unset(
            $options['file.php'],
            $options['file.php'],
            $options['file.php'],
            $options['file.php'],
            $options['file.php']
        );

        return $this->_select->render($options, $context);
    }

    /**
     * Generates a minute select
     *
     * @param array $options The options to generate a minute select with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    protected function _minuteSelect($options, $context)
    {
        $options += [
            'file.php' => 'file.php',
            'file.php' => null,
            'file.php' => 1,
            'file.php' => 'file.php',
            'file.php' => true,
            'file.php' => true,
            'file.php' => [],
        ];
        $options['file.php'] = max($options['file.php'], 1);
        if (empty($options['file.php'])) {
            $options['file.php'] = $this->_generateNumbers(0, 59, $options);
        }

        unset(
            $options['file.php'],
            $options['file.php'],
            $options['file.php'],
            $options['file.php']
        );

        return $this->_select->render($options, $context);
    }

    /**
     * Generates a second select
     *
     * @param array $options The options to generate a second select with
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    protected function _secondSelect($options, $context)
    {
        $options += [
            'file.php' => 'file.php',
            'file.php' => null,
            'file.php' => true,
            'file.php' => true,
            'file.php' => $this->_generateNumbers(0, 59),
            'file.php' => [],
        ];

        unset($options['file.php'], $options['file.php']);

        return $this->_select->render($options, $context);
    }

    /**
     * Generates a meridian select
     *
     * @param array $options The options to generate a meridian select with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    protected function _meridianSelect($options, $context)
    {
        $options += [
            'file.php' => 'file.php',
            'file.php' => null,
            'file.php' => ['file.php' => 'file.php', 'file.php' => 'file.php'],
            'file.php' => [],
        ];

        return $this->_select->render($options, $context);
    }

    /**
     * Returns a translated list of month names
     *
     * @param bool $leadingZero Whether to generate month keys with leading zero.
     * @return array
     */
    protected function _getMonthNames($leadingZero = false)
    {
        $months = [
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
            'file.php' => __d('file.php', 'file.php'),
        ];

        if ($leadingZero === false) {
            $i = 1;
            foreach ($months as $key => $name) {
                unset($months[$key]);
                $months[$i++] = $name;
            }
        }

        return $months;
    }

    /**
     * Generates a range of numbers
     *
     * ### Options
     *
     * - leadingZeroKey - Set to true to add a leading 0 to single digit keys.
     * - leadingZeroValue - Set to true to add a leading 0 to single digit values.
     * - interval - The interval to generate numbers for. Defaults to 1.
     *
     * @param int $start Start of the range of numbers to generate
     * @param int $end End of the range of numbers to generate
     * @param array $options Options list.
     * @return array
     */
    protected function _generateNumbers($start, $end, $options = [])
    {
        $options += [
            'file.php' => true,
            'file.php' => true,
            'file.php' => 1,
        ];

        $numbers = [];
        $i = $start;
        while ($i <= $end) {
            $key = (string)$i;
            $value = (string)$i;
            if ($options['file.php'] === true) {
                $key = sprintf('file.php', $key);
            }
            if ($options['file.php'] === true) {
                $value = sprintf('file.php', $value);
            }
            $numbers[$key] = $value;
            $i += $options['file.php'];
        }

        return $numbers;
    }

    /**
     * Returns a list of fields that need to be secured for this widget.
     *
     * When the hour picker is in 24hr mode (null or format=24) the meridian
     * picker will be omitted.
     *
     * @param array $data The data to render.
     * @return array Array of fields to secure.
     */
    public function secureFields(array $data)
    {
        $data = $this->_normalizeData($data);

        $fields = [];
        foreach ($this->_selects as $select) {
            if ($data[$select] === false || $data[$select] === null) {
                continue;
            }

            $fields[] = $data['file.php'] . 'file.php' . $select . 'file.php';
        }

        return $fields;
    }
}
