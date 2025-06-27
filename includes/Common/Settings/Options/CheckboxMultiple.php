<?php

namespace RRZE\PluginBlueprint\Common\Settings\Options;

use RRZE\PluginBlueprint\Common\Settings\Options\Type;

defined('ABSPATH') || exit;

/**
 * CheckboxMultiple option type for settings.
 *
 * This class represents a multiple checkbox option in the settings section.
 * It extends the Type class and provides methods to get the value
 * and sanitize the input as an array.
 *
 * @package RRZE\PluginBlueprint\Common\Settings\Options
 */
class CheckboxMultiple extends Type
{
    /**
     * The template used for rendering the multiple checkboxes.
     *
     * @var string
     */
    public $template = 'checkbox-multiple';

    /**
     * Gets the name attribute for the multiple checkboxes.
     *
     * This method appends '[]' to the name attribute to indicate that
     * it is an array of values.
     *
     * @return string The modified name attribute.
     */
    public function getNameAttribute()
    {
        $name = parent::getNameAttribute();

        return "{$name}[]";
    }

    /**
     * Gets the value of the multiple checkboxes.
     *
     * This method retrieves the value from the options and ensures that
     * it returns an array. If no value is set, it returns an array with
     * the default value.
     *
     * @return array The value of the multiple checkboxes.
     */
    public function getValueAttribute()
    {
        $value = get_option($this->section->tab->settings->optionName)[$this->getArg('name')] ?? false;
        if ($value === false) {
            $value = [$this->getArg('default')];
        }
        return $value;
    }

    /**
     * Sanitizes the input value as an array.
     *
     * This method converts the input value to an array, ensuring that
     * it can handle both single values and arrays of values.
     *
     * @param mixed $value The input value to sanitize.
     * @return array The sanitized value as an array.
     */
    public function sanitize($value)
    {
        return (array) $value;
    }
}
