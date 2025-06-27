<?php

namespace RRZE\PluginBlueprint\Common\Settings;

defined('ABSPATH') || exit;

/**
 * Template class for rendering settings templates.
 *
 * This class provides a method to include templates with variables passed to them.
 * It is used to render the settings pages of the plugin.
 *
 * @package RRZE\PluginBlueprint\Common\Settings
 */
class Template
{
    /**
     * Includes a template file with the given variables.
     *
     * This method includes a template file from the templates directory and
     * passes the provided variables to it. The output is captured and filtered
     * before being echoed.
     *
     * @param string $fileName The name of the template file to include (without extension).
     * @param array $vars An associative array of variables to pass to the template.
     * * @return void
     */
    public static function include($fileName, $vars = [])
    {
        foreach ($vars as $name => $value) {
            ${$name} = $value;
        }

        $path = __DIR__ . "/templates/{$fileName}.php";
        if (!file_exists($path)) {
            return;
        }

        ob_start();

        include $path;

        echo apply_filters('rrze_plugin_blueprint_settings_template_include', ob_get_clean(), $fileName, $vars);
    }
}
