<?php

namespace RRZE\PluginBlueprint\Common\Settings\Options;

use RRZE\PluginBlueprint\Common\Settings\Options\Type;

defined('ABSPATH') || exit;

/**
 * Textarea option type for settings.
 *
 * This class represents a textarea input option in the settings section.
 * It extends the Type class and provides a template for rendering the textarea input.
 *
 * @package RRZE\PluginBlueprint\Common\Settings\Options
 */
class Textarea extends Type
{
    /**
     * The template used for rendering the textarea input.
     *
     * This property defines the template file that will be used to render
     * the textarea input in the settings section.
     *
     * @var string
     */
    public $template = 'textarea';

    /**
     * Gets the value of the textarea input.
     *
     * This method retrieves the value from the options and ensures that
     * it returns a string. If no value is set, it returns an empty string.
     *
     * @return string The value of the textarea input.
     */
    public function sanitize($value)
    {
        return sanitize_textarea_field($value);
    }
}
