<?php

namespace RRZE\PluginBlueprint\Common\Settings;

defined('ABSPATH') || exit;

/**
 * Flash message handler for settings.
 *
 * This class manages flash messages for settings, allowing to set and check
 * the status and message associated with a specific settings option.
 *
 * @package RRZE\PluginBlueprint\Common\Settings
 */
class Flash
{
    /**
     * The settings instance associated with this flash handler.
     *
     * This property holds the settings instance that this flash handler is
     * associated with, allowing it to manage flash messages specific to those settings.
     *
     * @var Settings
     */
    public $settings;

    /**
     * Constructor for the Flash class.
     *
     * This method initializes the flash handler with the provided settings instance.
     * It allows setting and checking flash messages related to the settings options.
     *
     * @param Settings $settings The settings instance to associate with this flash handler.
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Checks if a flash message exists for the settings option.
     *
     * This method checks if there is a flash message set for the specific
     * settings option associated with this flash handler.
     *
     * @return array|null Returns the flash message if it exists, or null if not.
     */
    public function has()
    {
        global $wp_settings_flash;

        return $wp_settings_flash[$this->settings->optionName] ?? null;
    }

    /**
     * Sets a flash message for the settings option.
     *
     * This method allows setting a flash message with a status and message
     * for the specific settings option associated with this flash handler.
     *
     * @param string $status The status of the flash message (e.g., 'success', 'error').
     * @param string $message The message to be displayed in the flash.
     * @return void
     */
    public function set($status, $message)
    {
        global $wp_settings_flash;

        $wp_settings_flash[$this->settings->optionName] = compact('status', 'message');
    }
}
