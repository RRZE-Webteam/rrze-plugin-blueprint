<?php

namespace RRZE\PluginBlueprint\Common\CPT;

defined('ABSPATH') || exit;

/**
 * Custom Post Type registration class.
 *
 * Usage:
 * new CPT('book', [
 *     'labels' => [ ... ],
 *     'public' => true,
 *     // ...other arguments to register_post_type
 * ]);
 */
class CPT
{
    /**
     * The post type slug.
     *
     * @var string
     */
    protected $postType;

    /**
     * Arguments for register_post_type.
     *
     * @var array
     */
    protected $args;

    /**
     * Constructor.
     *
     * @param string $postType The post type slug.
     * @param array $args Arguments for register_post_type.
     * @return void
     */
    public function __construct(string $postType, array $args = [])
    {
        $this->postType = $postType;
        $this->args = $args;

        add_action('init', [$this, 'register']);
    }

    /**
     * Registers the custom post type.
     *
     * This method checks if the post type already exists before registering it.
     * It is hooked to the 'init' action to ensure it runs at the right time in the WordPress lifecycle.
     *
     * @return void
     */
    public function register()
    {
        if (!post_type_exists($this->postType)) {
            register_post_type($this->postType, $this->args);
        }
    }

    /**
     * Returns the post type slug.
     *
     * This method can be used to retrieve the post type slug for further use,
     * such as in templates or other parts of the plugin.
     *
     * @return string The post type slug.
     */
    public function getPostType()
    {
        return $this->postType;
    }
}
