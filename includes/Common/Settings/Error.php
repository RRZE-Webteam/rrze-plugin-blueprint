<?php

namespace RRZE\PluginBlueprint\Common\Settings;

use RRZE\PluginBlueprint\Common\Settings\Settings;

defined('ABSPATH') || exit;

/**
 * Error handling for settings.
 *
 * This class manages errors related to settings options.
 * It allows adding, retrieving, and checking for errors in the settings context.
 *
 * @package RRZE\PluginBlueprint\Common\Settings
 */
class Error
{
    /**
     * The settings instance associated with this error handler.
     *
     * This property holds the settings instance that this error handler is
     * associated with, allowing it to manage errors specific to those settings.
     *
     * @var Settings
     */
    public $settings;

    /**
     * The WP_Error instance used to store errors.
     *
     * This property holds the WP_Error instance that will be used to manage
     * errors related to the settings options.
     *
     * @var \WP_Error
     */
    public $error;

    /**
     * Constructor for the Error class.
     *
     * This method initializes the error handler with the provided settings instance.
     * It also creates a new WP_Error instance to manage errors.
     *
     * @param Settings $settings The settings instance to associate with this error handler.
     */
    public function __construct(Settings $settings)
    {
        $this->error = new \WP_Error;
        $this->settings = $settings;
    }

    /**
     * Retrieves all errors associated with the settings.
     *
     * This method checks for any errors related to the settings options and
     * returns them as a WP_Error instance. If no errors are found, it returns
     * an empty WP_Error instance.
     *
     * @return \WP_Error The WP_Error instance containing all errors, or an empty instance if none exist.
     */
    public function getAll(): \WP_Error
    {
        global $wp_settings_error;

        if (isset($wp_settings_error[$this->settings->optionName]) && is_wp_error($wp_settings_error[$this->settings->optionName])) {
            return $wp_settings_error[$this->settings->optionName];
        }

        return new \WP_Error();
    }

    /**
     * Retrieves a specific error message by key.
     *
     * This method checks for a specific error message associated with the given key
     * in the settings errors. If the key exists, it returns the error message; otherwise,
     * it returns null.
     *
     * @param string $key The key of the error message to retrieve.
     * @return string|null The error message if found, or null if not found.
     */
    public function get(string $key): ?string
    {
        $errors = $this->getAll();

        return is_wp_error($errors) ? $errors->get_error_message($key) : null;
    }

    /**
     * Adds an error message for a specific key.
     *
     * This method adds an error message associated with the given key to the settings errors.
     * It also updates the global settings error array to ensure the error is stored correctly.
     *
     * @param string $key The key for the error message.
     * @param string $message The error message to add.
     */
    public function add(string $key, string $message): void
    {
        $this->error->add($key, $message);

        global $wp_settings_error;
        $wp_settings_error[$this->settings->optionName] = $this->error;
    }

    /**
     * Checks if there are any errors in the settings.
     *
     * This method checks if there are any errors associated with the settings options.
     * It returns true if there are errors, and false otherwise.
     *
     * @return bool True if there are errors, false otherwise.
     */
    public function hasErrors(): bool
    {
        return ! empty($this->getAll()->errors);
    }
}
