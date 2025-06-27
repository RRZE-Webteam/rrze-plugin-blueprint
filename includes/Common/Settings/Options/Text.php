<?php

namespace RRZE\PluginBlueprint\Common\Settings\Options;

use RRZE\PluginBlueprint\Common\Settings\Options\Type;

defined('ABSPATH') || exit;

/**
 * Text option type for settings.
 *
 * This class represents a text input option in the settings section.
 * It extends the Type class and provides a template for rendering the text input.
 *
 * @package RRZE\PluginBlueprint\Common\Settings\Options
 */
class Text extends Type
{
    /**
     * The template used for rendering the text input.
     *
     * This property defines the template file that will be used to render
     * the text input in the settings section.
     *
     * @var string
     */
    public $template = 'text';
}
