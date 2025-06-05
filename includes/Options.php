<?php

namespace RRZE\PluginBlueprint;

defined('ABSPATH') || exit;

/**
 * Options class
 * 
 * This class handles the plugin options, providing methods to retrieve, set, and delete options.
 * It defines default options and ensures that only valid keys are returned when retrieving options.
 * The options are stored in the WordPress database under a specific option name.
 * 
 * @package RRZE\PluginBlueprint
 * 
 * @since 1.0.0
 * 
 * @example
 * // Get the default options
 * $defaultOptions = Options::getDefaultOptions();
 * 
 * // Get the current options from the database
 * $currentOptions = Options::getOptions();
 * 
 * // Delete the options from the database
 * Options::deleteOption();
 * 
 * // Get the option name
 * $optionName = Options::getOptionName();
 */
class Options
{
    /**
     * Option name
     * 
     * @var string
     */
    protected static $optionName = 'rrze_plugin_blueprint_postmeta';

    /**
     * Default options
     * 
     * @return array
     */
    protected static function defaultOptions(): array
    {
        $options = [
            'checkbox_1' => 0,
            'textfield_1' => '',
            'textarea_1' => '',
        ];

        return $options;
    }

    /**
     * Returns the default options.
     * 
     * @return array
     */
    public static function getDefaultOptions(): array
    {
        return self::defaultOptions();
    }

    /**
     * Returns the options.
     * This method retrieves the plugin options from the database.
     * It merges the stored options with the default options and ensures that only valid keys are returned.
     * If the option does not exist, it will return the default options.
     * 
     * @return array
     */
    public static function getOptions(): array
    {
        $defaults = self::defaultOptions();
        $options = (array) get_option(self::$optionName);
        $options = wp_parse_args($options, $defaults);
        $options = array_intersect_key($options, $defaults);

        return $options;
    }

    /**
     * Returns the name of the option.
     * This method provides the name of the option used to store plugin settings in the database.
     * 
     * @return string
     */
    public static function getOptionName(): string
    {
        return self::$optionName;
    }

    /**
     * Deletes the option.
     * This method removes the option from the database.
     *
     * @return bool True on success, false on failure.
     */
    public static function deleteOption(): bool
    {
        return delete_option(self::$optionName);
    }
}
