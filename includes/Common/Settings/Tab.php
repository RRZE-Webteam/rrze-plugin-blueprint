<?php

namespace RRZE\PluginBlueprint\Common\Settings;

use RRZE\PluginBlueprint\Common\Settings\Section;

defined('ABSPATH') || exit;

/**
 * Class Tab
 *
 * Represents a settings tab in the plugin settings.
 * Each tab can contain multiple sections, and each section can have various options.
 *
 * @package RRZE\PluginBlueprint\Common\Settings
 */
class Tab
{
    /**
     * The settings associated with this tab.
     *
     * This property holds the settings object that contains all the settings
     * for this tab, allowing access to the settings values and methods.
     *
     * @var \RRZE\PluginBlueprint\Common\Settings\Settings
     */
    public $settings;

    /**
     * The title of the tab.
     *
     * This property holds the title of the tab, which is displayed in the settings
     * interface to identify the tab.
     *
     * @var string
     */
    public $title;

    /**
     * The slug of the tab.
     *
     * This property holds the slug of the tab, which is used in URLs and for
     * identifying the tab in the settings interface.
     *
     * @var string|null
     */
    public $slug;

    /**
     * The sections contained within this tab.
     *
     * This property holds an array of Section objects that represent the sections
     * within this tab. Each section can contain various options and settings.
     *
     * @var Section[]
     */
    public $sections = [];

    /**
     * Tab constructor.
     *
     * Initializes the tab with the given settings, title, and optional slug.
     *
     * @param \RRZE\PluginBlueprint\Common\Settings\Settings $settings The settings object for this tab.
     * @param string $title The title of the tab.
     * @param string|null $slug The slug of the tab (optional).
     * @return void
     */
    public function __construct($settings, $title, $slug = null)
    {
        $this->title = $title;
        $this->settings = $settings;

        if ($this->slug === null) {
            $this->slug = sanitize_title($title);
        }
    }

    /**
     * Adds a section to the tab.
     *
     * This method creates a new Section object with the given title and optional arguments,
     * and adds it to the sections array of this tab.
     *
     * @param string $title The title of the section.
     * @param array $args Optional arguments for the section.
     * @return Section The created Section object.
     */
    public function addSection($title, $args = [])
    {
        $section = new Section($this, $title, $args);

        $this->sections[] = $section;

        return $section;
    }

    /**
     * Retrieves all sections in this tab.
     *
     * This method returns an array of all Section objects contained within this tab.
     *
     * @return Section[] An array of Section objects.
     */
    public function getSectionLinks()
    {
        return array_filter($this->sections, function ($section) {
            return $section->asLink;
        });
    }

    /**
     * Checks if the tab contains only section links.
     *
     * This method determines if all sections in this tab are links (i.e., they have
     * the `asLink` property set to true). It returns true if all sections are links,
     * otherwise false.
     *
     * @return bool True if all sections are links, false otherwise.
     */
    public function containsOnlySectionLinks()
    {
        return count($this->getSectionLinks()) === count($this->sections);
    }

    /**
     * Retrieves a section by its name (slug).
     *
     * This method searches through the sections in this tab and returns the section
     * that matches the given name (slug). If no matching section is found, it returns false.
     *
     * @param string $name The slug of the section to retrieve.
     * @return Section|false The matching Section object or false if not found.
     */
    public function getSectionByName($name)
    {
        foreach ($this->sections as $section) {
            if ($section->slug == $name) {
                return $section;
            }
        }

        return false;
    }

    /**
     * Retrieves the active section based on the request parameters.
     *
     * This method checks if a section is specified in the request parameters
     * and returns the corresponding Section object. If no section is specified,
     * it returns the first section if all sections are links.
     *
     * @return Section|null The active Section object or null if no active section is found.
     */
    public function getActiveSection()
    {
        if (empty($this->getSectionLinks())) {
            return;
        }

        if (isset($_REQUEST['section'])) {
            return $this->getSectionByName($_REQUEST['section']);
        }

        if ($this->containsOnlySectionLinks()) {
            return $this->sections[0];
        }
    }

    /**
     * Retrieves the active sections based on the request parameters.
     *
     * This method checks if a section is specified in the request parameters
     * and returns an array of Section objects that match the specified section.
     * If no section is specified, it returns all sections that are not links.
     *
     * @return Section[] An array of active Section objects.
     */
    public function getActiveSections()
    {
        if (!isset($_REQUEST['section']) && $this->containsOnlySectionLinks()) {
            return [$this->sections[0]];
        }

        return \array_filter($this->sections, function ($section) {
            if (isset($_REQUEST['section'])) {
                return $section->asLink && $_REQUEST['section'] == $section->slug;
            }

            return !$section->asLink;
        });
    }
}
