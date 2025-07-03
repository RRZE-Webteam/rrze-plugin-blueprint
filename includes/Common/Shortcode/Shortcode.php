<?php

namespace RRZE\PluginBlueprint\Common\Shortcode;

defined('ABSPATH') || exit;

/**
 * Generic Shortcode registration class.
 *
 * Usage:
 * $shortcode = new Shortcode('my_shortcode');
 * To register the shortcode, you can hook it into the 'init' action:
 * $shortcode->register(fn($atts, $content = null, $tag = '') =>
 *     '<div class="my-shortcode">' . esc_html($atts['title']) . '</div>'
 * );
 *
 * @package RRZE\PluginBlueprint\Common\Shortcode
 */
class Shortcode
{
    /**
     * The shortcode tag.
     *
     * @var string
     */
    protected string $tag;

    /**
     * Constructor.
     * Initializes the shortcode with a tag.
     * The tag is used to identify the shortcode in the content.
     *
     * @param string $tag The shortcode tag.
     * @return void
     */
    public function __construct(string $tag)
    {
        $this->tag = $tag;
    }

    /**
     * Registers the shortcode.
     *
     * It uses the add_shortcode function to associate the tag with the callback function.
     * This should be a callable that accepts three parameters:
     * - $atts (array): The shortcode attributes.
     * - $content (string): The content inside the shortcode.
     * - $tag (string): The shortcode tag.
     * This method should be called during the 'init' action hook to ensure that WordPress is ready to register shortcodes.
     *
     * @param callable $callback The callback function for the shortcode.
     * @return void
     */
    public function register(callable $callback)
    {
        add_shortcode($this->tag, $callback);
    }
}
