<?php

namespace RRZE\PluginBlueprint\Common\Settings\Options;

use RRZE\PluginBlueprint\Common\Settings\{
    Section,
    Template
};

defined('ABSPATH') || exit;

/**
 * Abstract class representing a type of option in the settings.
 *
 * This class serves as a base for different types of options, providing common
 * functionality such as rendering, sanitization, and validation.
 *
 * @package RRZE\PluginBlueprint\Common\Settings\Options
 */
abstract class Type
{
    /**
     * The section this option belongs to.
     *
     * @var Section
     */
    public $section;

    /**
     * Arguments for the option.
     *
     * This array holds various arguments that define the behavior and appearance
     * of the option, such as label, name, id, placeholder, and CSS classes.
     *
     * @var array
     */
    public $args = [];

    /**
     * The template used for rendering the option.
     *
     * This property defines the template file that will be used to render
     * the option in the settings section. It should be set in subclasses.
     *
     * @var string
     */
    public $template;

    /**
     * Constructor for the Type class.
     *
     * Initializes the option with a section and optional arguments.
     *
     * @param Section $section The section this option belongs to.
     * @param array $args Optional arguments for the option.
     */
    public function __construct($section, $args = [])
    {
        $this->section = $section;
        $this->args = $args;
    }

    /**
     * Renders the option using the specified template.
     *
     * This method includes the template file for rendering the option,
     * passing the current option instance as a variable to the template.
     *
     * @return string The rendered HTML output of the option.
     */
    public function render()
    {
        return Template::include('options/' . $this->template, ['option' => $this]);
    }

    /**
     * Checks if the option has an error.
     *
     * This method checks if there is an error associated with the option
     * in the settings errors collection.
     *
     * @return bool True if there is an error, false otherwise.
     */
    public function hasError()
    {
        return $this->section->tab->settings->errors->get($this->getArg('name'));
    }

    /**
     * Sanitizes the input value.
     *
     * This method should be overridden in subclasses to provide specific
     * sanitization logic for different types of options.
     *
     * @param mixed $value The value to sanitize.
     * @return mixed The sanitized value.
     */
    public function sanitize($value)
    {
        return sanitize_text_field($value);
    }

    /**
     * Validates the input value.
     *
     * This method should be overridden in subclasses to provide specific
     * validation logic for different types of options.
     *
     * @param mixed $value The value to validate.
     * @return bool True if the value is valid, false otherwise.
     */
    public function validate($value)
    {
        return true;
    }

    /**
     * Retrieves an argument value by key.
     *
     * This method checks if the specified key exists in the args array
     * and returns its value. If the key does not exist, it returns a fallback value.
     *
     * @param string $key The key of the argument to retrieve.
     * @param mixed $fallback The fallback value if the key does not exist.
     * @return mixed The value of the argument or the fallback value.
     */
    public function getArg($key, $fallback = null)
    {
        if (empty($this->args[$key])) {
            return $fallback;
        }

        if (is_callable($this->args[$key])) {
            return $this->args[$key]();
        }

        return $this->args[$key];
    }

    /**
     * Gets the label for the option.
     *
     * This method retrieves the label argument and escapes it for safe output.
     *
     * @return string The escaped label for the option.
     */
    public function getLabel()
    {
        return esc_attr($this->getArg('label'));
    }

    /**
     * Gets the ID attribute for the option.
     *
     * This method generates a sanitized ID based on the name of the option,
     * replacing brackets with underscores to ensure it is a valid HTML ID.
     *
     * @return string The sanitized ID attribute for the option.
     */
    public function getIdAttribute()
    {
        return $this->getArg('id', sanitize_title(str_replace('[', '_', $this->getNameAttribute())));
    }

    /**
     * Gets the name of the option.
     *
     * This method retrieves the name argument for the option.
     *
     * @return string The name of the option.
     */
    public function getName()
    {
        return $this->getArg('name');
    }

    /**
     * Gets the placeholder attribute for the option.
     *
     * This method retrieves the placeholder argument and returns it if set,
     * otherwise returns null.
     *
     * @return string|null The placeholder text or null if not set.
     */
    public function getPlaceholderAttribute()
    {
        $placeholder = $this->getArg('placeholder') ?? null;

        return $placeholder ?: null;
    }

    /**
     * Gets the CSS classes for the option.
     *
     * This method retrieves the CSS classes defined in the args array.
     * It returns an array of classes that can be used for styling the option.
     *
     * @return array The CSS classes for the option.
     */
    public function getCss()
    {
        return $this->getArg('css', []);
    }

    /**
     * Gets the input class attribute for the option.
     *
     * This method retrieves the input class from the CSS array and returns it
     * as a string formatted for use in an HTML class attribute.
     *
     * @return string|null The input class attribute or null if not set.
     */
    public function getInputClassAttribute()
    {
        $class = $this->getCss()['input_class'] ?? null;

        return !empty($class) ? 'class="' . esc_attr($class) . '"' : null;
    }

    /**
     * Gets the label class attribute for the option.
     *
     * This method retrieves the label class from the CSS array and returns it
     * as a string formatted for use in an HTML class attribute.
     *
     * @return string|null The label class attribute or null if not set.
     */
    public function getLabelClassAttribute()
    {
        $class = $this->getCss()['label_class'] ?? null;

        return !empty($class) ? 'class="' . esc_attr($class) . '"' : null;
    }

    /**
     * Gets the name attribute for the option.
     *
     * This method constructs the name attribute for the option, which is used
     * in form submissions. It includes the section's tab settings option name
     * and the specific name of the option.
     *
     * @return string The name attribute for the option.
     */
    public function getNameAttribute()
    {
        return $this->section->tab->settings->optionName . '[' . $this->getArg('name') . ']';
    }

    /**
     * Gets the value of the option.
     *
     * This method retrieves the value of the option from the WordPress options table.
     * If the value is not set, it returns the default value specified in the args.
     *
     * @return mixed The value of the option or null if not set.
     */
    public function getValueAttribute()
    {
        $value = get_option($this->section->tab->settings->optionName)[$this->getArg('name')] ?? false;

        return $value ? $value : $this->args['default'] ?? null;
    }
}
