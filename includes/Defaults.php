<?php

namespace RRZE\PluginBlueprint;

use function RRZE\PluginBlueprint\plugin;

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
     * This method loads the default values for the plugin, which can include
     * custom post types, taxonomies, settings, and other plugin-specific defaults.
     * The defaults are filtered through `rrze_plugin_blueprint_defaults` to allow
     * for customization by other plugins or themes.
     * Do not use translation functions (__(), _e(), _x(), etc.) or apply internationalization filters in this method, 
     * as the translation system is not initialized at this point in the plugin lifecycle.
     *
     * @return array
     */
    private function load(): array
    {
        return apply_filters('rrze_plugin_blueprint_defaults', [
            'cpt' => [
                'name'          => $this->withPrefix('book'),
                'taxonomy_name' => $this->withPrefix('genre'),
            ],
            'settings' => [
                'option_name'       => 'rrze_plugin_blueprint_settings',
                'capability'        => 'manage_options',
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

    /**
     * Prepends a deterministic, 6-char unique prefix to any key.
     *
     * @param string $key The raw key to namespace.
     * @return string The 6-char-prefixed key.
     */
    public function withPrefix(string $key = ''): string
    {
        $rawSlug = plugin()->getSlug();
        $clean = preg_replace('/[^a-z0-9]/', '', $rawSlug);

        $keep = min(3, strlen($clean));
        $part = substr($clean, 0, $keep);

        $needed = 6 - strlen($part);
        $hash = substr(md5($clean), 0, $needed);

        $prefix = $part . $hash;

        if (! preg_match('/^[a-z]/', $prefix)) {
            $prefix = 'p' . substr($prefix, 0, 5);
        }

        return $prefix . '_' . sanitize_key($key);
    }
}
