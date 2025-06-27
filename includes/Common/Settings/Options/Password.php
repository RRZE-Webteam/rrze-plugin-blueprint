<?php

namespace RRZE\PluginBlueprint\Common\Settings\Options;

use RRZE\PluginBlueprint\Common\Settings\Options\Type;
use RRZE\PluginBlueprint\Common\Settings\Encryption;

defined('ABSPATH') || exit;

/**
 * Password option type for settings.
 *
 * This class represents a password input field in the settings section.
 * It extends the Type class and provides methods to get the value
 * and sanitize the input using encryption.
 *
 * @package RRZE\PluginBlueprint\Common\Settings\Options
 */
class Password extends Type
{
    /**
     * The template used for rendering the password input.
     *
     * @var string
     */
    public $template = 'password';

    /**
     * Gets the name attribute for the password input.
     *
     * This method appends '[]' to the name attribute to indicate that
     * it is an array of values.
     *
     * @return string The modified name attribute.
     */
    public function getValueAttribute()
    {
        $value = get_option($this->section->tab->settings->optionName)[$this->getArg('name')] ?? false;

        return $value ? Encryption::decrypt($value) : null;
    }

    /**
     * Sanitizes the input value by encrypting it.
     *
     * This method uses the Encryption class to encrypt the value before saving it.
     * It is called when the settings are saved to ensure that sensitive data is stored securely.
     *
     * @param mixed $value The value to sanitize.
     * @return string The encrypted value.
     */
    public function sanitize($value)
    {
        return Encryption::encrypt($value);
    }
}
