<?php

namespace RRZE\PluginBlueprint\Common\Settings\Options;

use RRZE\PluginBlueprint\Common\Settings\Options\Type;

defined('ABSPATH') || exit;

/**
 * Select option type for settings.
 *
 * This class represents a select dropdown option in the settings section.
 * It extends the Type class and provides a template for rendering the select input.
 *
 * @package RRZE\PluginBlueprint\Common\Settings\Options
 */
class Select extends Type
{
    /**
     * The template used for rendering the select input.
     *
     * This property defines the template file that will be used to render
     * the select input in the settings section.
     *
     * @var string
     */
    public $template = 'select';
}
