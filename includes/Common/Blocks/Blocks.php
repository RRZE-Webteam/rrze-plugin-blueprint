<?php

namespace RRZE\PluginBlueprint\Common\Blocks;

defined('ABSPATH') || exit;

/**
 * Blocks class
 * 
 * This class is responsible for registering custom blocks for the plugin.
 * 
 * @package RRZE\PluginBlueprint
 * @since 1.0.0
 */
class Blocks
{
    /**
     * @var array $blocks An array of block names to be registered.
     */
    private $blocks;

    /**
     * @var string $blockPath The path to the blocks directory.
     */
    private $blockPath;

    /**
     * @var string $pluginPath The path to the plugin directory.
     */
    private $pluginPath;

    /**
     * Constructor for the Blocks class.
     * This constructor initializes the blocks property with the provided array of block names.
     * It also sets up the necessary hooks to register the blocks and enqueue their scripts.
     * @param array $blocks An array of block names to be registered.
     * @param string $blockPath The path to the blocks directory.
     * @param string $pluginPath The path to the plugin directory.
     * @return void
     */
    public function __construct(array $blocks = [], string $blockPath = '', string $pluginPath = '')
    {
        // Ensure that the blocks array is not empty.
        if (empty($blocks)) {
            return;
        }

        // Set the blocks property to the provided array.
        // This allows for dynamic registration of blocks based on the provided array.
        $this->blocks = $blocks;

        // Ensure that the blocks directory exists.
        if (!is_dir($blockPath)) {
            return;
        }

        $this->blockPath = rtrim($blockPath, '/') . '/';

        // Ensure that the plugin path is set correctly.
        if (!is_dir($pluginPath)) {
            return;
        }

        // Ensure that the plugin path is set correctly.
        $this->pluginPath = rtrim($pluginPath, '/') . '/';

        // Register the blocks when WordPress initializes.
        add_action('init', [$this, 'register']);

        // Enqueue block editor assets for the blocks.
        add_action('enqueue_block_editor_assets', [$this, 'setBlockScriptTranslations']);
    }

    /**
     * Registers custom blocks for the plugin.
     * 
     * This method registers both static and dynamic blocks for the plugin.
     * 
     * @return void
     */
    public function register()
    {
        foreach ($this->blocks as $block) {
            register_block_type($this->blockPath . $block);
        }
    }

    /**
     * Sets script translations for the blocks.
     * 
     * This method sets the translations for the block editor scripts,
     * allowing for localization of the block strings.
     * 
     * @return void
     */
    public function setBlockScriptTranslations()
    {
        foreach ($this->blocks as $block) {
            wp_set_script_translations(
                'rrze-plugin-blueprint-' . $block . '-editor-script',
                'rrze-plugin-blueprint',
                $this->pluginPath . 'languages'
            );
        }
    }
}
