<?php

namespace RRZE\PluginBlueprint;

defined('ABSPATH') || exit;

/**
 * Main class for the RRZE Plugin Blueprint.
 * 
 * This class serves as the main entry point for the plugin, handling initialization and settings.
 * It sets up hooks, initializes options, and provides a settings link in the plugin action links.
 * 
 * @package RRZE\PluginBlueprint
 * @since 1.0.0
 */
class Main
{
    /**
     * @var \RRZE\PluginBlueprint\Options
     */
    protected $options;

    /**
     * @var \RRZE\PluginBlueprint\Settings
     */
    protected $settings;

    /**
     * Constructor for the Main class.
     * 
     * This method initializes the plugin by loading options and settings,
     * and adds a settings link to the plugin action links.
     * It can also be used to initialize other components or modules of the plugin.
     * 
     * @return void
     */
    public function __construct()
    {
        // Add the settings link to the plugin action links.
        add_filter('plugin_action_links_' . plugin()->getBaseName(), [$this, 'settingsLink']);

        // Optionally initialize options and settings.
        $this->options = (object) Options::getOptions();
        $this->settings = new Settings();

        // Initialize other modules or components as needed.
        // For example, you can initialize a custom post type, taxonomy, or any other functionality.        
    }

    /**
     * Add a settings link to the plugin action links.
     * 
     * @param array $links
     * @return array
     */
    public function settingsLink($links)
    {
        $settingsLink = sprintf(
            '<a href="%s">%s</a>',
            admin_url('options-general.php?page=' . $this->settings->getMenuPage()),
            __('Settings', 'rrze-plugin-blueprint')
        );
        array_unshift($links, $settingsLink);
        return $links;
    }
}
