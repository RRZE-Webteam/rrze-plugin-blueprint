<?php

namespace RRZE\PluginBlueprint\Common\Settings\Options;

use RRZE\PluginBlueprint\Common\Settings\Options\Type;

defined('ABSPATH') || exit;

/**
 * Checkbox option type for settings.
 *
 * This class represents a checkbox option in the settings section.
 * It extends the Type class and provides methods to get the value
 * and check if the checkbox is checked.
 *
 * @package RRZE\PluginBlueprint\Common\Settings\Options
 */
class Checkbox extends Type
{
    /**
     * The template used for rendering the checkbox.
     *
     * @var string
     */
    public $template = 'checkbox';

    /**
     * The default value for the checkbox.
     *
     * @var mixed
     */
    public function getValueAttribute()
    {
        $value = get_option($this->section->tab->settings->optionName)[$this->getArg('name')] ?? false;
        if ($value === false) {
            $value = $this->getArg('default');
        }
        return $value;
    }

    /**
     * Checks if the checkbox is checked.
     *
     * This method returns true if the checkbox is checked (i.e., the value is true),
     * and false otherwise.
     *
     * @return bool True if the checkbox is checked, false otherwise.
     */
    public function isChecked()
    {
        return (bool) $this->getValueAttribute();
    }
}
