<?php

namespace RRZE\PluginBlueprint\Common\Settings\Options;

use RRZE\PluginBlueprint\Common\Settings\Options\Type;

defined('ABSPATH') || exit;

/**
 * SelectMultiple option type for settings.
 *
 * This class represents a multiple select option in the settings section.
 * It extends the Type class and provides methods to get the value and sanitize input.
 *
 * @package RRZE\PluginBlueprint\Common\Settings\Options
 */
class SelectMultiple extends Type
{
    /**
     * The template used for rendering the multiple select input.
     *
     * This property defines the template file that will be used to render
     * the multiple select input in the settings section.
     *
     * @var string
     */
    public $template = 'select-multiple';

    /**
     * Gets the name attribute for the multiple select input.
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
     * Gets the value of the multiple select input.
     *
     * This method retrieves the value from the options and ensures that
     * it returns an array. If no value is set, it returns an array with
     * the default value.
     *
     * @return array The value of the multiple select input.
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
     * This method ensures that the value is returned as an array, which is
     * useful for multiple select inputs where multiple options can be selected.
     *
     * @param mixed $value The value to sanitize.
     * @return array The sanitized value as an array.
     */
    public function sanitize($value)
    {
        return (array) $value;
    }
}
