<?php

namespace RRZE\PluginBlueprint;

defined('ABSPATH') || exit;

use RRZE\PluginBlueprint\Settings;

/**
 * Main class
 * 
 * This class serves as the entry point for the plugin.
 * It can be extended to include additional functionality or components as needed.
 * 
 * @package RRZE\PluginBlueprint
 * @since 1.0.0
 */
class Main
{
    /**
     * Constructor for the Main class.
     * 
     * This method initializes the plugin by loading (optionally) the settings.
     * It can also be used to initialize other components of the plugin.
     * 
     * @return void
     */
    public function __construct()
    {
        // Optionally initialize settings.
        new Settings();

        // Initialize other components as needed.
        // For example, you can initialize a custom post type, taxonomy, or any other functionality.        
    }
}
