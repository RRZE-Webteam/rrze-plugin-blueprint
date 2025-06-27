<?php

namespace RRZE\PluginBlueprint\Common\Shortcode;

defined('ABSPATH') || exit;

/**
 * Generic Shortcode registration class.
 *
 * Usage:
 * new Shortcode('shortcode_tag', function($atts, $content, $tag) {
 *     // Your shortcode logic here
 *     return 'Hello World!';
 * });
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
     * The callback function for the shortcode.
     *
     * This should be a callable that accepts three parameters:
     * - $atts (array): The shortcode attributes.
     * - $content (string): The content inside the shortcode.
     * - $tag (string): The shortcode tag.
     *
     * @var callable
     */
    protected $callback;

    /**
     * Constructor.
     *
     * @param string   $tag      The shortcode tag.
     * @param callable $callback The callback function for the shortcode.
     * @return void
     */
    public function __construct(string $tag, callable $callback)
    {
        $this->tag = $tag;
        $this->callback = $callback;

        add_action('init', [$this, 'register']);
    }

    /**
     * Registers the shortcode.
     *
     * This method hooks into the 'init' action to register the shortcode.
     * It uses the add_shortcode function to associate the tag with the callback.
     *
     * @return void
     */
    public function register()
    {
        add_shortcode($this->tag, $this->callback);
    }
}
