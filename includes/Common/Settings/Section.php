<?php

namespace RRZE\PluginBlueprint\Common\Settings;

use RRZE\PluginBlueprint\Common\Settings\{
    Option,
    Tab
};

defined('ABSPATH') || exit;

/**
 * Section class for managing settings sections.
 *
 * This class represents a section in the settings page, allowing the addition
 * of options and defining properties such as title, slug, and description.
 *
 * @package RRZE\PluginBlueprint\Common\Settings
 */
class Section
{
    /**
     * The tab this section belongs to.
     *
     * This property holds the Tab instance that this section is part of,
     * allowing for organization of sections within tabs in the settings page.
     *
     * @var Tab
     */
    public $tab;

    /**
     * Indicates whether the section should be displayed as a link.
     *
     * This property determines if the section is displayed as a link in the
     * settings page, allowing for easier navigation between sections.
     *
     * @var bool
     */
    public $asLink;

    /**
     * The title of the section.
     *
     * This property holds the title of the section, which is displayed in the
     * settings page to identify the section.
     *
     * @var string
     */
    public $title;

    /**
     * The arguments for the section.
     *
     * This property holds additional arguments for the section, such as slug
     * and description, which can be used to customize the section's behavior
     * and appearance.
     *
     * @var array
     */
    public $args;

    /**
     * The slug of the section.
     *
     * This property defines a unique identifier for the section, which is used
     * in URLs and for referencing the section in code.
     *
     * @var string
     */
    public $slug;

    /**
     * The description of the section.
     *
     * This property holds a brief description of the section, which can be
     * displayed in the settings page to provide context or instructions to
     * users.
     *
     * @var string|null
     */
    public $description;

    /**
     * The options in the section.
     *
     * This property holds an array of options that belong to this section,
     * allowing for the addition and management of various settings options
     * within the section.
     *
     * @var array
     */
    public $options = [];

    /**
     * Constructor for the Section class.
     *
     * Initializes the section with a tab, title, and optional arguments.
     * Sets the slug and description based on the provided arguments or defaults.
     *
     * @param string $tab The Tab instance this section belongs to.
     * @param string $title The title of the section.
     * @param array $args Optional arguments for the section (slug, description, as_link).
     */
    public function __construct(Tab $tab, $title, $args = [])
    {
        $this->tab = $tab;
        $this->title = $title;
        $this->args = $args;
        $this->slug = $this->args['slug'] ?? sanitize_title($title);
        $this->description = $this->args['description'] ?? null;
        $this->asLink = $this->args['as_link'] ?? false;
    }

    /**
     * Adds an option to the section.
     *
     * This method creates a new Option instance and adds it to the section's
     * options array. The type and arguments for the option can be specified.
     *
     * @param string $type The type of the option (e.g., 'checkbox', 'text').
     * @param array $args Optional arguments for the option.
     * @return Option The created Option instance.
     */
    public function addOption($type, $args = [])
    {
        $option = new Option($this, $type, $args);

        $this->options[] = $option;

        return $option;
    }
}
