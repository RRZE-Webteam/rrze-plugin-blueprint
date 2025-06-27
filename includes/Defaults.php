<?php

namespace RRZE\PluginBlueprint;

defined('ABSPATH') || exit;

/**
 * Class Defaults
 *
 * Holds and provides access to plugin-wide default values.
 *
 * @package RRZE\PluginBlueprint
 */
class Defaults
{
    /**
     * Plugin default values.
     *
     * @var array
     */
    private readonly array $defaults;

    /**
     * Defaults constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->defaults = $this->load();
    }

    /**
     * Returns the default values, filtered via WordPress.
     *
     * @return array
     */
    private function load(): array
    {
        return apply_filters('rrze_plugin_blueprint_defaults', [
            'cpt' => [
                'name'          => 'book',
                'taxonomy_name' => 'genre',
            ],
            'settings' => [
                'option_name'       => 'rrze_plugin_blueprint_settings',
                'menu_title'        => __('Plugin Blueprint', 'rrze-plugin-blueprint'),
                'page_title'        => __('RRZE Plugin Blueprint Settings', 'rrze-plugin-blueprint'),
                'capability'        => 'manage_options',
                'checkbox_option'   => false,
                'text_placeholder'  => __('Enter your text here...', 'rrze-plugin-blueprint'),
                'select_default'    => 'none',
            ]
            // Add more defaults as needed
        ]);
    }

    /**
     * Retrieve a default value by key.
     *
     * @param string $key The key of the default.
     * @return mixed|null The value if found, or null.
     */
    public function get(string $key): mixed
    {
        return $this->defaults[$key] ?? null;
    }

    /**
     * Get all defaults.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->defaults;
    }
}
