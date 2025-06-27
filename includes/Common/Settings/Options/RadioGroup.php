<?php

namespace RRZE\PluginBlueprint\Common\Settings\Options;

use RRZE\PluginBlueprint\Common\Settings\Options\Type;

defined('ABSPATH') || exit;

/**
 * RadioGroup option type for settings.
 *
 * This class represents a group of radio buttons in the settings section.
 * It extends the Type class and provides a template for rendering the radio group.
 *
 * @package RRZE\PluginBlueprint\Common\Settings\Options
 */
class RadioGroup extends Type
{
    /**
     * The template used for rendering the radio group.
     *
     * This property defines the template file that will be used to render
     * the radio group in the settings section.
     *
     * @var string
     */
    public $template = 'radio-group';
}
