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
                'name'          => $this->withPrefix('book'),
                'taxonomy_name' => $this->withPrefix('genre'),
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

    /**
     * Prepends a deterministic, 6-char unique prefix to any key.
     *
     * @param string $key The raw key to namespace.
     * @return string The 6-char-prefixed key.
     */
    function withPrefix(string $key = ''): string
    {
        $rawSlug = plugin()->getSlug();
        $clean   = preg_replace('/[^a-z0-9]/', '', $rawSlug);

        $keep = min(3, strlen($clean));
        $part = substr($clean, 0, $keep);

        $needed   = 6 - strlen($part);
        $hash_part = substr(md5($clean), 0, $needed);

        $prefix = $part . $hash_part;

        if (! preg_match('/^[a-z]/', $prefix)) {
            $prefix = 'p' . substr($prefix, 0, 5);
        }

        return $prefix . '_' . sanitize_key($key);
    }
}
