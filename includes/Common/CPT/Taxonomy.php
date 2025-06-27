<?php

namespace RRZE\PluginBlueprint\Common\CPT;

defined('ABSPATH') || exit;

/**
 * Taxonomy registration class.
 *
 * Usage:
 * new Taxonomy('genre', 'book', [
 *     'labels' => [ ... ],
 *     'public' => true,
 * ]);
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
     * Arguments for register_taxonomy.
     *
     * @var array
     */
    protected $args;

    /**
     * @param string $taxonomy The taxonomy slug.
     * @param string|array $objectType The post type(s) to attach the taxonomy to.
     * @param array $args Arguments for register_taxonomy.
     * @return void
     */
    public function __construct(string $taxonomy, $objectType, array $args = [])
    {
        $this->taxonomy = $taxonomy;
        $this->objectType = $objectType;
        $this->args = $args;

        add_action('init', [$this, 'register']);
    }

    /**
     * Registers the taxonomy.
     *
     * This method checks if the taxonomy already exists before registering it.
     * It is hooked to the 'init' action to ensure it runs at the right time in the WordPress lifecycle.
     *
     * @return void
     */
    public function register()
    {
        if (!taxonomy_exists($this->taxonomy)) {
            register_taxonomy($this->taxonomy, $this->objectType, $this->args);
        }
    }
}
