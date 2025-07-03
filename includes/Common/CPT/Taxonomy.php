<?php

namespace RRZE\PluginBlueprint\Common\CPT;

defined('ABSPATH') || exit;

/**
 * Taxonomy registration class.
 *
 * Usage:
 * $taxonomy = new Taxonomy('my_taxonomy', 'book');
 * add_action('init', fn() =>
 *     $taxonomy->register([
 *         'label' => __('My Taxonomy', 'textdomain'),
 *         'public' => true,
 *         'hierarchical' => true,
 *         'show_in_rest' => true,
 *     ])
 * );
 *
 * @package RRZE\PluginBlueprint\Common\CPT
 */
class Taxonomy
{
    /**
     * The taxonomy slug.
     *
     * @var string
     */
    protected $taxonomy;

    /**
     * The post type(s) to attach the taxonomy to.
     *
     * This can be a single post type slug or an array of post type slugs.
     *
     * @var string|array
     */
    protected $objectType;

    /**
     * @param string $taxonomy The taxonomy slug.
     * @param string|array $objectType The post type(s) to attach the taxonomy to.
     * @return void
     */
    public function __construct(string $taxonomy, $objectType)
    {
        $this->taxonomy = $taxonomy;
        $this->objectType = $objectType;
    }

    /**
     * Registers the taxonomy.
     *
     * This method checks if the taxonomy already exists before registering it.
     * It must be called during the 'init' action hook to ensure that WordPress is ready to register taxonomies.
     *
     * @param array $args Arguments for register_taxonomy.
     * @return void
     */
    public function register(array $args = [])
    {
        if (!taxonomy_exists($this->taxonomy)) {
            register_taxonomy($this->taxonomy, $this->objectType, $args);
        }
    }
}
