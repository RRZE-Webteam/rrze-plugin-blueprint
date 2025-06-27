<?php

namespace RRZE\PluginBlueprint\Common\Settings;

defined('ABSPATH') || exit;

use RRZE\PluginBlueprint\Common\Settings\Section;

use RRZE\PluginBlueprint\Common\Settings\Options\{
    Checkbox,
    CheckboxMultiple,
    Password,
    RadioGroup,
    Select,
    SelectMultiple,
    Text,
    Textarea
};

class Option
{
    /**
     * The section this option belongs to.
     * 
     * @var Section
     */
    public $section;

    /**
     * The type of the option.
     * 
     * @var string
     */
    public $type;

    /**
     * Arguments for the option.
     * 
     * @var array
     */
    public $args = [];

    /**
     * The implementation of the option type.
     * 
     * @var mixed
     */
    public $implementation;

    /**
     * Constructor for the Option class.
     *
     * Initializes the option with a section, type, and optional arguments.
     *
     * @param Section $section The section this option belongs to.
     * @param string $type The type of the option.
     * @param array $args Optional arguments for the option.
     */
    public function __construct(Section $section, string $type, array $args = [])
    {
        $this->section = $section;
        $this->type = $type;
        $this->args = $args;

        $typeMap = apply_filters('rrze_plugin_blueprint_settings_option_type_map', [
            'checkbox' => Checkbox::class,
            'checkbox-multiple' => CheckboxMultiple::class,
            'password' => Password::class,
            'radio-group' => RadioGroup::class,
            'select' => Select::class,
            'select-multiple' => SelectMultiple::class,
            'text' => Text::class,
            'textarea' => Textarea::class
        ]);

        if (isset($typeMap[$this->type])) {
            $this->implementation = new $typeMap[$this->type]($section, $args);
        } else {
            $this->implementation = null;
        }
    }

    /**
     * Returns the value of an argument or a fallback value.
     *
     * This method retrieves the value of a specific argument by its key.
     * If the argument does not exist, it returns the provided fallback value.
     *
     * @param string $key The key of the argument to retrieve.
     * @param mixed $fallback The fallback value if the argument is not set.
     * @return mixed The value of the argument or the fallback value.
     */
    public function getArg($key, $fallback = null)
    {
        return $this->args[$key] ?? $fallback;
    }

    /**
     * Gets the label for the option.
     *
     * This method retrieves the label argument and escapes it for safe output.
     *
     * @return string The escaped label of the option.
     */
    public function sanitize($value)
    {
        if (is_callable($this->getArg('sanitize'))) {
            return $this->getArg('sanitize')($value);
        }

        return is_null($this->implementation) ?: $this->implementation->sanitize($value);
    }

    /**
     * Validates the input value.
     *
     * This method checks if the value is valid based on the validation rules defined
     * in the arguments. It can handle multiple validation callbacks and will add errors
     * to the settings errors if validation fails.
     *
     * @param mixed $value The value to validate.
     * @return bool True if the value is valid, false otherwise.
     */
    public function validate($value)
    {
        if (is_array($this->getArg('validate'))) {
            foreach ($this->getArg('validate') as $validate) {
                if (!is_callable($validate['callback'])) {
                    continue;
                }

                $valid = $validate['callback']($value);

                if (!$valid) {
                    $this->section->tab->settings->errors->add($this->getArg('name'), $validate['feedback']);

                    return false;
                }
            }

            return true;
        }

        if (is_callable($this->getArg('validate'))) {
            return $this->getArg('validate')($value);
        }

        return is_null($this->implementation) ?: $this->implementation->validate($value);
    }

    /**
     * Renders the option.
     *
     * This method outputs the HTML for the option based on its type and arguments.
     * It checks if the option is visible before rendering and allows for custom rendering
     * through a callback function.
     * 
     * @return void
     */
    public function render()
    {
        if (is_callable($this->getArg('visible')) && $this->getArg('visible')() === false) {
            return;
        }

        if (is_callable($this->getArg('render'))) {
            echo $this->getArg('render')($this->implementation);

            return;
        }

        echo is_null($this->implementation) ?: $this->implementation->render();
    }
}
