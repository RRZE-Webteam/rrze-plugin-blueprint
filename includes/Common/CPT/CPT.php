<?php

namespace RRZE\PluginBlueprint\Common\CPT;

defined('ABSPATH') || exit;

/**
 * Custom Post Type registration class.
 * 
 * This class provides a simple way to register custom post types in WordPress.
 *
 * Usage:
 * $cpt = new CPT('my_cpt');
 * add_action('init', fn() =>
 *     $cpt->register([
 *         'label' => __('My CPT', 'textdomain'),
 *         'public' => true,
 *         'has_archive' => true,
 *         'show_in_rest' => true,
 *         'supports' => ['title', 'editor', 'thumbnail'],
 *     ])
 * );
 *
 * @package RRZE\PluginBlueprint\Common\CPT
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
     * Constructor.
     *
     * @param string $postType The post type slug.
     * @return void
     */
    public function __construct(string $postType)
    {
        $this->postType = $postType;
    }

    /**
     * Registers the custom post type.
     *
     * This method checks if the post type already exists before registering it.
     * It must be called during the 'init' action hook to ensure that WordPress is ready to register post types.
     *
     * @param array $args Arguments for register_post_type.
     * @return void
     */
    public function register(array $args = [])
    {
        if (!post_type_exists($this->postType)) {
            register_post_type($this->postType, $args);
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
