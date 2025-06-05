<?php

/*
Plugin Name:        RRZE Plugin Blueprint
Plugin URI:         https://github.com/RRZE-Webteam/rrze-plugin-blueprint
Version:            1.0.0
Description:        A blueprint for creating WordPress plugins with a focus on multilingual support and best practices.
Author:             RRZE Webteam
Author URI:         https://blogs.fau.de/webworking/
License:            GNU General Public License Version 3
License URI:        https://www.gnu.org/licenses/gpl-3.0.html
Text Domain:        rrze-plugin-blueprint
Domain Path:        /languages
Requires at least:  6.8
Requires PHP:       8.2
*/

namespace RRZE\PluginBlueprint;

use RRZE\PluginBlueprint\Main;
use RRZE\PluginBlueprint\Plugin;

// Prevent direct access to the file.
// This line ensures that the file is only executed within the context of WordPress.
// If accessed directly, it will exit the script to prevent unauthorized access.
defined('ABSPATH') || exit;

/**
 * SPL Autoloader (PSR-4).
 * 
 * This autoloader function is registered with the SPL autoload stack to automatically load classes
 * from the plugin's 'includes' directory based on their fully-qualified class names.
 * It follows the PSR-4 autoloading standard, where the namespace corresponds to the directory structure.
 * It maps the namespace prefix to the base directory of the plugin, allowing for easy class loading
 * without the need for manual `require` or `include` statements.
 * This autoloader is particularly useful for organizing plugin code into classes and namespaces,
 * promoting better code structure and maintainability.
 * Use require_once `vendor/autoload.php` instead if you are using Composer for autoloading.
 * 
 * @see https://www.php-fig.org/psr/psr-4/
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {
    $prefix = __NAMESPACE__;
    $baseDir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Register activation hook for the plugin
register_activation_hook(__FILE__, __NAMESPACE__ . '\activation');

// Register deactivation hook for the plugin
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\deactivation');

/**
 * Add an action hook for the 'plugins_loaded' hook.
 *
 * This hook is triggered after all active plugins have been loaded, allowing the plugin to perform
 * initialization tasks.
 */
add_action('plugins_loaded', __NAMESPACE__ . '\loaded');

/**
 * Activation callback function.
 * 
 * @return void
 */
function activation()
{
    // Use this if you need to perform tasks on activation.
}

/**
 * Deactivation callback function.
 */
function deactivation()
{
    // Use this if you need to perform tasks on deactivation.
    // For example, you might want to clean up options or scheduled events.
}

/**
 * Singleton pattern for initializing and accessing the main plugin instance.
 *
 * This method ensures that only one instance of the Plugin class is created and returned.
 *
 * @return Plugin The main instance of the Plugin class.
 */
function plugin()
{
    // Declare a static variable to hold the instance.
    static $instance;

    // Check if the instance is not already created.
    if (null === $instance) {
        // Add a new instance of the Plugin class, passing the current file (__FILE__) as a parameter.
        $instance = new Plugin(__FILE__);
    }

    // Return the main instance of the Plugin class.
    return $instance;
}

/**
 * Callback function to load the plugin textdomain.
 * 
 * @return void
 */
function load_textdomain()
{
    load_plugin_textdomain(
        'rrze-plugin-blueprint',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
}

/**
 * Check system requirements for the plugin.
 *
 * This method checks if the server environment meets the minimum WordPress and PHP version requirements
 * for the plugin to function properly.
 *
 * @return string An error message string if requirements are not met, or an empty string if requirements are satisfied.
 */
function systemRequirements(): string
{
    // Initialize an error message string.
    $error = '';

    // Check if the WordPress version is compatible with the plugin's requirement.
    if (version_compare(get_bloginfo('version'), plugin()->getRequiresWP(), '<')) {
        $error = sprintf(
            /* translators: 1: Server WordPress version number, 2: Required WordPress version number. */
            __('The server is running WordPress version %1$s. The plugin requires at least WordPress version %2$s.', 'rrze-plugin-blueprint'),
            get_bloginfo('version'),
            plugin()->getRequiresWP()
        );
    } elseif (version_compare(PHP_VERSION, plugin()->getRequiresPHP(), '<')) {
        // Check if the PHP version is compatible with the plugin's requirement.
        $error = sprintf(
            /* translators: 1: Server PHP version number, 2: Required PHP version number. */
            __('The server is running PHP version %1$s. The plugin requires at least PHP version %2$s.', 'rrze-plugin-blueprint'),
            PHP_VERSION,
            plugin()->getRequiresPHP()
        );
    }

    // Return the error message string, which will be empty if requirements are satisfied.
    return $error;
}

/**
 * Handle the loading of the plugin.
 *
 * This function is responsible for initializing the plugin, loading text domains for localization,
 * checking system requirements, and displaying error notices if necessary.
 */
function loaded()
{
    // Trigger the 'loaded' method of the main plugin instance.
    plugin()->loaded();

    // Load the plugin textdomain for translations.
    add_action(
        'init',
        __NAMESPACE__ . '\load_textdomain'
    );

    // Check system requirements and store any error messages.
    if ($error = systemRequirements()) {
        // If there is an error, add an action to display an admin notice with the error message.
        add_action('admin_init', function () use ($error) {
            // Check if the current user has the capability to activate plugins.
            if (current_user_can('activate_plugins')) {
                // Get plugin data to retrieve the plugin's name.
                $pluginName = plugin()->getName();

                // Determine the admin notice tag based on network-wide activation.
                $tag = is_plugin_active_for_network(plugin()->getBaseName()) ? 'network_admin_notices' : 'admin_notices';

                // Add an action to display the admin notice.
                add_action($tag, function () use ($pluginName, $error) {
                    printf(
                        '<div class="notice notice-error"><p>' .
                            /* translators: 1: The plugin name, 2: The error string. */
                            esc_html__('Plugins: %1$s: %2$s', 'rrze-plugin-blueprint') .
                            '</p></div>',
                        $pluginName,
                        $error
                    );
                });
            }
        });

        // Return to prevent further initialization if there is an error.
        return;
    }

    // If there are no errors, create an instance of the 'Main' class and trigger its 'loaded' method.
    (new Main)->loaded();
}
