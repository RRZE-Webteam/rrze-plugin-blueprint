<?php

namespace RRZE\PluginBlueprint\Common\Settings;

use RRZE\PluginBlueprint\Common\Settings\{
    Builder,
    Error,
    Flash,
    Option,
    Section,
    Tab,
    Template,
    Worker
};

defined('ABSPATH') || exit;

/**
 * Class Settings
 *
 * This class represents a settings page in the WordPress admin area.
 * It allows for the creation and management of settings, tabs, sections, and options.
 * It also handles saving settings, rendering the settings page, and managing errors and flash messages.
 *
 * @package RRZE\PluginBlueprint\Common\Settings
 */
class Settings
{
    /**
     * @var string $title The title of the settings page.
     * 
     * This property is used to set the main title of the settings page that will be displayed
     * in the WordPress admin area. It is typically a descriptive name for the settings being managed.
     */
    public $title;

    /**
     * @var string $menuTitle The title for the settings page in the WordPress admin menu.
     * 
     * This property is used to set the title that will be displayed in the admin menu.
     * If not provided, it defaults to the main title of the settings page.
     */
    public $menuTitle;

    /**
     * @var string $slug The slug for the settings page.
     * 
     * This property is used to uniquely identify the settings page in the WordPress admin menu.
     * If not provided, it will be generated from the title of the settings page.
     */
    public $slug;

    /**
     * @var string $parentSlug The slug of the parent menu for this settings page.
     * 
     * This property is used to define a parent menu under which this settings page will be added.
     * If set, the settings page will appear as a submenu under the specified parent menu.
     */
    public $parentSlug;

    /**
     * @var string $capability The capability required to access the settings page.
     * 
     * This property defines the user capability required to view and modify the settings page.
     * The default is 'manage_options', which is typically reserved for administrators.
     */
    public $capability = 'manage_options';

    /**
     * @var string $menuIcon The icon for the menu in the WordPress admin.
     * 
     * This property can be set to a dashicon class or a URL to an image that will be used
     * as the icon for the settings page in the WordPress admin menu.
     */
    public $menuIcon;

    /**
     * @var int $menuPosition The position of the menu in the WordPress admin.
     * 
     * This property determines where the settings page will appear in the admin menu.
     * It can be set to a specific integer value to control the order of the menu items.
     */
    public $menuPosition;

    /**
     * @var string $optionName The name of the option used to store settings in the WordPress options table.
     * 
     * This property is automatically generated from the title of the settings page,
     * ensuring a unique identifier for storing and retrieving settings.
     */
    public $optionName;

    /**
     * @var Tab[] $tabs An array of Tab instances for the settings page.
     * 
     * This property holds all the tabs defined for the settings page. Each tab can contain multiple sections
     * and options, allowing for organized and structured settings management.
     */
    public $tabs = [];

    /**
     * @var Error $errors The error handler for the settings page.
     * 
     * This property is used to handle errors that occur during the settings process,
     * such as validation errors or issues with saving settings.
     */
    public $errors;

    /**
     * @var Flash $flash The flash message handler for the settings page.
     * 
     * This property is used to handle flash messages, which are temporary messages
     * displayed to the user after performing actions like saving settings.
     */
    public $flash;

    /**
     * Constructor for the Settings class.
     *
     * This method initializes the settings page with a title and an optional slug.
     * The slug is used to create a unique identifier for the settings page in the WordPress admin.
     *
     * @param string $title The title of the settings page.
     * @param string|null $slug Optional slug for the settings page. If not provided, it will be generated from the title.
     * @return void
     */
    public function __construct($title, $slug = null)
    {
        $this->title = $title;
        $this->optionName = strtolower(str_replace('-', '_', sanitize_title($this->title)));
        $this->slug = $slug;

        if ($this->slug === null) {
            $this->slug = sanitize_title($title);
        }
    }

    /**
     * Sets the parent slug for the settings page.
     *
     * This method allows you to specify a parent menu under which this settings page will be added.
     * If set, the settings page will appear as a submenu under the specified parent menu.
     *
     * @param string $slug The slug of the parent menu.
     * @return $this
     */
    public function setMenuParentSlug($slug)
    {
        $this->parentSlug = $slug;
        return $this;
    }

    /**
     * Sets the title for the settings page.
     *
     * This method allows you to specify a custom title for the settings page.
     * The title is used in the admin menu and on the settings page itself.
     *
     * @param string $title The title of the settings page.
     * @return $this
     */
    public function setMenuTitle($title)
    {
        $this->menuTitle = $title;
        return $this;
    }

    /**
     * Returns the title for the menu in the WordPress admin.
     *
     * This method checks if a custom menu title is set. If not, it defaults to the main title.
     * It is used to display the title of the settings page in the admin menu.
     *
     * @return string The title to be displayed in the admin menu.
     */
    public function getMenuTitle()
    {
        return $this->menuTitle ?? $this->title;
    }

    /**
     * Sets the capability required to access the settings page.
     *
     * This method allows you to specify a custom capability that users must have
     * in order to view and modify the settings page. The default capability is 'manage_options'.
     *
     * @param string $capability The capability required to access the settings page.
     * @return $this
     */
    public function setCapability($capability)
    {
        $this->capability = $capability;
        return $this;
    }

    /**
     * Sets the option name for the settings.
     *
     * This method allows you to specify a custom option name that will be used
     * to store the settings in the WordPress options table.
     *
     * @param string $name The name of the option to be used for storing settings.
     * @return $this
     */
    public function setOptionName($name)
    {
        $this->optionName = $name;
        return $this;
    }

    /**
     * Sets the icon for the menu in the WordPress admin.
     *
     * This method allows you to specify a custom icon for the settings page menu.
     * The icon can be a dashicon class or a URL to an image.
     *
     * @param string $icon The icon for the menu, either a dashicon class or an image URL.
     * @return $this
     */
    public function setMenuIcon($icon)
    {
        $this->menuIcon = $icon;
        return $this;
    }

    /**
     * Sets the position of the menu in the WordPress admin.
     *
     * This method allows you to specify the position of the settings page in the admin menu.
     * The position is an integer that determines where the menu will appear.
     *
     * @param int $position The position of the menu in the admin menu.
     * @return $this
     */
    public function setMenuPosition($position)
    {
        $this->menuPosition = $position;
        return $this;
    }

    /**
     * Adds the settings page to the WordPress admin menu.
     *
     * This method registers the settings page with WordPress, allowing it to be displayed
     * in the admin menu. It can either add a top-level menu or a submenu under an existing menu.
     *
     * @return void
     */
    public function addToMenu()
    {
        if ($this->parentSlug) {
            add_submenu_page(
                $this->parentSlug,
                $this->title,
                $this->getMenuTitle(),
                $this->capability,
                $this->slug,
                [$this, 'render'],
                $this->menuPosition
            );
        } else {
            add_menu_page(
                $this->title,
                $this->getMenuTitle(),
                $this->capability,
                $this->slug,
                [$this, 'render'],
                $this->menuIcon,
                $this->menuPosition
            );
        }
    }

    /**
     * Initializes the settings by setting up actions and filters.
     *
     * This method is called to set up the necessary hooks for the settings page,
     * including saving settings, adding to the admin menu, and applying custom styles.
     *
     * @return void
     */
    public function build()
    {
        $this->errors = new Error($this);
        $this->flash = new Flash($this);
    }

    /**
     * Checks if the current screen is the settings page.
     *
     * This method retrieves the current screen and checks if its base matches
     * the settings page slug. It returns true if it is on the settings page,
     * otherwise false.
     *
     * @return bool True if on settings page, false otherwise.
     */
    public function isOnSettingsPage()
    {
        $screen = get_current_screen();
        if (is_null($screen)) {
            return false;
        }

        if ($screen->base === 'settings_page_' . $this->slug) {
            return true;
        }

        return false;
    }

    /**
     * Outputs custom styling for the settings page.
     *
     * This method checks if the current screen is the settings page and outputs
     * custom CSS styles to enhance the appearance of error messages.
     *
     * @return void
     */
    public function styling()
    {
        if (!$this->isOnSettingsPage()) {
            return;
        }

        echo '<style>.rrze-plugin-blueprint-settings-error {color: #d63638; margin: 5px 0;}</style>';
    }

    /**
     * Retrieves a tab by its slug.
     *
     * This method iterates through the defined tabs and returns the Tab instance
     * that matches the provided slug. If no matching tab is found, it returns false.
     *
     * @param string $slug The slug of the tab to retrieve.
     * @return Tab|false The Tab instance if found, or false if not found.
     */
    public function getTabBySlug($slug)
    {
        foreach ($this->tabs as $tab) {
            if ($tab->slug === $slug) {
                return $tab;
            }
        }

        return false;
    }

    /**
     * Returns the active tab based on the current request.
     *
     * This method checks if a 'tab' parameter is present in the query string.
     * If it is, it returns the corresponding Tab instance if it exists.
     * If not, it returns the first tab or false if no tabs are defined.
     *
     * @return Tab|false The active Tab instance or false if no tabs are defined.
     */
    public function getActiveTab()
    {
        $default = $this->tabs[0] ?? false;

        if (isset($_GET['tab'])) {
            return in_array($_GET['tab'], array_map(function ($tab) {
                return $tab->slug;
            }, $this->tabs)) ? $this->getTabBySlug($_GET['tab']) : $default;
        }

        return $default;
    }

    /**
     * Adds a new tab to the settings.
     *
     * This method creates a new Tab instance and adds it to the settings.
     * It allows for defining multiple tabs within the settings page.
     *
     * @param string $title The title of the tab.
     * @param string|null $slug Optional slug for the tab. If not provided, it will be generated from the title.
     * @return Tab The created Tab instance.
     */
    public function addTab($title, $slug = null)
    {
        $tab = new Tab($this, $title, $slug);

        $this->tabs[] = $tab;

        return $tab;
    }

    /**
     * Adds a section to the last tab.
     *
     * This method allows adding a new section to the currently active tab.
     * If there are no tabs defined, it creates a new unnamed tab first.
     *
     * @param string $title The title of the section.
     * @param array $args Optional arguments for the section.
     * @return Section The created Section instance.
     */
    public function addSection($title, $args = [])
    {
        if (empty($this->tabs)) {
            $tab = $this->addTab(__('Unnamed tab', 'rrze-plugin-blueprint'));
        } else {
            $tab = end($this->tabs);
        }

        return $tab->addSection($title, $args);
    }

    /**
     * Adds an option to the last section of the last tab.
     *
     * This method allows adding a new option to the currently active section of the last tab.
     * It returns false if there is no active tab or section to add the option to.
     *
     * @param string $type The type of the option (e.g., 'text', 'checkbox').
     * @param array $args Optional arguments for the option, such as label, name, default value, etc.
     * @return bool|Option Returns the created Option instance or false if it fails.
     */
    public function addOption($type, $args = [])
    {
        $tab = end($this->tabs);

        if (!$tab instanceof Tab) {
            return false;
        }

        $section = end($tab->sections);

        if (!$section instanceof Section) {
            return false;
        }

        return $section->addOption($type, $args);
    }

    /**
     * Checks if the settings should be displayed as tabs.
     *
     * This method determines whether the settings have more than one tab defined.
     * If there are multiple tabs, it returns true, indicating that tabs should be displayed.
     *
     * @return bool True if there are multiple tabs, false otherwise.
     */
    public function shouldMakeTabs()
    {
        return count($this->tabs) > 1;
    }

    /**
     * Returns the URL for the settings page.
     *
     * This method constructs the URL for the settings page based on the slug and parent slug.
     * If a parent slug is set and it contains '.php', it will use that as the base URL.
     *
     * @return string The URL for the settings page.
     */
    public function getUrl()
    {
        if ($this->parentSlug && strpos($this->parentSlug, '.php') !== false) {
            return add_query_arg('page', $this->slug, admin_url($this->parentSlug));
        }

        return admin_url("admin.php?page=$this->slug");
    }

    /**
     * Returns the full URL for the settings page, including active tab and section.
     *
     * This method constructs the URL for the settings page, appending query parameters
     * for the active tab and section if they are set.
     *
     * @return string The full URL for the settings page with active tab and section.
     */
    public function getFullUrl()
    {
        $params = [];

        if ($active_tab = $this->getActiveTab()) {
            $params['tab'] = $active_tab->slug;

            if ($active_section = $active_tab->getActiveSection()) {
                $params['section'] = $active_section->slug;
            }
        }

        return add_query_arg($params, $this->getUrl());
    }

    /**
     * Renders the tab menu if there are multiple tabs.
     *
     * This method checks if there are multiple tabs defined in the settings.
     * If so, it includes the tab menu template to display the tabs.
     *
     * @return void
     */
    public function renderTabMenu()
    {
        if (!$this->shouldMakeTabs()) {
            return;
        }

        Template::include('tab-menu', ['settings' => $this]);
    }

    /**
     * Renders the active sections of the current tab.
     *
     * This method includes the sections template and passes the current settings instance to it.
     * It is typically called when rendering the settings page to display the sections under the active tab.
     *
     * @return void
     */
    public function renderActiveSections()
    {
        Template::include('sections', ['settings' => $this]);
    }

    /**
     * Renders the settings page.
     *
     * This method initializes the Worker and Builder, enqueues necessary scripts,
     * and includes the settings page template for rendering.
     *
     * @return void
     */
    public function render()
    {
        Worker::setBuilder(new Builder);

        Worker::enqueue();

        Template::include('settings-page', ['settings' => $this]);
    }

    /**
     * Saves the settings from the admin page.
     *
     * This method processes the submitted settings, validates them, and updates the options in the database.
     * It also handles user permissions and displays success messages upon successful saving.
     *
     * @return void
     */
    public function save()
    {
        if (
            !isset($_POST['rrze_plugin_blueprint_settings_save'])
            || !wp_verify_nonce(
                $_POST['rrze_plugin_blueprint_settings_save'],
                'rrze_plugin_blueprint_settings_save_' . $this->optionName
            )
        ) {
            return;
        }

        if (!current_user_can($this->capability)) {
            wp_die(__('You do not have enough permissions to do that.', 'rrze-plugin-blueprint'));
        }

        $currentOptions = $this->getOptions();
        $submittedOptions = apply_filters('rrze_plugin_blueprint_settings_new_options', $_POST[$this->optionName] ?? [], $currentOptions);
        $newOptions = $currentOptions;

        foreach ($this->getActiveTab()->getActiveSections() as $section) {
            foreach ($section->options as $option) {
                $value = $submittedOptions[$option->implementation->getName()] ?? null;

                $valid = $option->validate($value);

                if (!$valid) {
                    continue;
                }

                $value = apply_filters('rrze_plugin_blueprint_settings_new_option_' . $option->implementation->getName(), $option->sanitize($value), $option->implementation);

                $newOptions[$option->implementation->getName()] = $value;
            }
        }

        $this->updateOptions($newOptions);

        $this->flash->set('success', __('Settings saved.', 'rrze-plugin-blueprint'));
    }

    /**
     * Returns the default options for all settings.
     *
     * This method iterates through all tabs, sections, and options to collect their default values.
     * It returns an associative array where keys are option names and values are their defaults.
     *
     * @return array An associative array of default options.
     */
    public function defaultOptions()
    {
        $options = [];
        foreach ($this->tabs as $tab) {
            foreach ($tab->sections as $section) {
                foreach ($section->options as $option) {
                    $options[$option->args['name']] = $option->args['default'] ?? null;
                }
            }
        }

        return $options;
    }

    /**
     * Retrieves the current options for the settings.
     *
     * This method fetches the options from the WordPress database, merges them with default values,
     * and ensures that only valid options are returned.
     *
     * @return array An associative array of current options, merged with defaults.
     */
    public function getOptions()
    {
        $defaults = $this->defaultOptions();
        $options = get_option($this->optionName, []);
        $options = wp_parse_args($options, $defaults);
        $options = array_intersect_key($options, $defaults);

        return $options;
    }

    /**
     * Retrieves a specific option by its name.
     *
     * This method fetches the current options and returns the value for the specified option name.
     * If the option does not exist, it returns null.
     *
     * @param string $option The name of the option to retrieve.
     * @return mixed The value of the option, or null if it does not exist.
     */
    public function getOption($option)
    {
        $options = $this->getOptions();
        return $options[$option] ?? null;
    }

    /**
     * Updates the options in the WordPress database.
     *
     * This method saves the provided options to the WordPress database and triggers an action hook
     * after the options have been updated. It is typically called after saving settings from the admin page.
     *
     * @param array $options An associative array of options to update.
     * @return void
     */
    public function updateOptions($options)
    {
        update_option($this->optionName, $options);
        do_action('rrze_plugin_blueprint_settings_after_update_option', $this->optionName, $options);
    }
}
