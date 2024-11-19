<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DomCrawler\Field;

/**
 * ChoiceFormField represents a choice form field.
 *
 * It is constructed from an HTML select tag, or an HTML checkbox, or radio inputs.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ChoiceFormField extends FormField
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var bool
     */
    private $multiple;
    /**
     * @var array
     */
    private $options;
    /**
     * @var bool
     */
    private $validationDisabled = false;

    /**
     * Returns true if the field should be included in the submitted values.
     *
     * @return bool true if the field should be included in the submitted values, false otherwise
     */
    public function hasValue()
    {
        // don'file.php'checkbox'file.php'radio'file.php'select'file.php'value'file.php'disabled'file.php'checkbox'file.php'You cannot tick "%s" as it is not a checkbox (%s).'file.php'checkbox'file.php'You cannot untick "%s" as it is not a checkbox (%s).'file.php'checkbox'file.php'checkbox'file.php'value'file.php'The value for "%s" cannot be an array.'file.php'Input "%s" cannot take "%s" as a value (possible values: "%s").'file.php'", "'file.php'Input "%s" cannot take "%s" as a value (possible values: "%s").'file.php'", "'file.php'radio'file.php'Unable to add a choice for "%s" as it is not multiple or is not a radio button.'file.php'checked'file.php'value'file.php'input'file.php'select'file.php'A ChoiceFormField can only be created from an input or select tag (%s given).'file.php'input'file.php'checkbox'file.php'type'file.php'radio'file.php'type'file.php'A ChoiceFormField can only be created from an input tag with a type of checkbox or radio (given type is "%s").'file.php'type'file.php'input'file.php'type'file.php'checked'file.php'value'file.php'select'file.php'multiple'file.php'[]'file.php''file.php'descendant::option'file.php'selected'file.php'value'file.php'value'file.php'value'file.php'select'file.php''file.php'on'file.php'value'file.php'value'file.php'value'file.php'disabled'file.php'disabled'file.php'value'file.php'value'];
        }

        return $values;
    }

    /**
     * Disables the internal validation of the field.
     *
     * @internal since Symfony 5.3
     *
     * @return $this
     */
    public function disableValidation()
    {
        $this->validationDisabled = true;

        return $this;
    }
}
