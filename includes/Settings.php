<?php

namespace RRZE\PluginBlueprint;

defined('ABSPATH') || exit;

/**
 * Settings class
 * 
 * This class handles the plugin settings, providing methods to create an admin menu,
 * display the settings page, and register settings fields.
 * It allows users to configure plugin options through the WordPress admin interface.
 * 
 * @package RRZE\PluginBlueprint
 * @since 1.0.0
 * 
 * @example
 * // Create a new instance of the Settings class
 * $settings = new Settings();
 * 
 * // This will automatically add the settings page to the WordPress admin menu
 * // and register the settings fields defined in the class.
 */
class Settings
{
    /**
     * Option name for the plugin settings.
     * This is used to store and retrieve the plugin options from the WordPress database.
     *
     * @var string
     * @see Options::getOptionName()
     */
    protected $optionName;

    /**
     * Options object containing the current plugin settings.
     * This object is populated with the options retrieved from the database,
     * allowing easy access to the plugin settings throughout the class.
     *
     * @var object
     * @see Options::getOptions()
     */
    protected $options;

    /**
     * Menu page slug for the plugin settings.
     * This slug is used to identify the settings page in the WordPress admin menu.
     * It should be unique to avoid conflicts with other plugins or themes.
     *
     * @var string
     * @see adminMenu()
     * @see optionsPage()
     * @see adminInit()
     */
    protected $menuPage = 'rrze-plugin-blueprint-settings';

    /**
     * Constructor for the Settings class.
     * 
     * This method initializes the settings by retrieving the option name and current options.
     * It also sets up the necessary hooks to add the settings page to the WordPress admin menu
     * and register the settings fields.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->optionName = Options::getOptionName();
        $this->options = (object) Options::getOptions();

        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('admin_init', [$this, 'adminInit']);
    }

    /**
     * Get the menu page slug.
     * 
     * This method returns the menu page slug for the plugin settings.
     * 
     * @return string The menu page slug.
     */
    public function getMenuPage(): string
    {
        return $this->menuPage;
    }

    /**
     * Add the plugin settings page to the WordPress admin menu.
     * 
     * This method registers the settings page under the "Settings" menu in the WordPress admin.
     * It uses the `add_options_page` function to create a new submenu item for the plugin settings.
     * 
     * @return void
     */
    public function adminMenu()
    {
        add_options_page(
            __('Plugin Blueprint', 'rrze-plugin-blueprint'),
            __('Plugin Blueprint', 'rrze-plugin-blueprint'),
            'manage_options',
            $this->menuPage,
            [$this, 'optionsPage']
        );
    }

    /**
     * Display the plugin settings page.
     * 
     * This method outputs the HTML for the plugin settings page.
     * It includes a form that allows users to configure the plugin options.
     * The form submits to the WordPress options API for saving settings.
     * 
     * @return void
     */
    public function optionsPage()
    {
        echo '<div class="wrap">',
        '<h1>', esc_html(__('Plugin Blueprint Settings', 'rrze-plugin-blueprint')), '</h1>',
        '<form method="post" action="options.php">';

        do_settings_sections($this->menuPage);

        settings_fields($this->menuPage);

        submit_button();

        echo '</form>',
        '</div>';
    }

    /**
     * Register the plugin settings and add settings fields.
     * 
     * This method registers the plugin settings with the WordPress options API.
     * It adds a settings section and fields for the plugin options, allowing users to configure them.
     * The `sanitizeOptions` method is used to validate and sanitize the input before saving.
     * 
     * @return void
     */
    public function adminInit()
    {
        register_setting(
            $this->menuPage,
            $this->optionName,
            [$this, 'sanitizeOptions']
        );

        add_settings_section(
            'rrze_plugin_blueprint_general_section',
            __('General Settings', 'rrze-plugin-blueprint'),
            function () {
                echo '<p>', esc_html(__('General settings for the Plugin Blueprint.', 'rrze-plugin-blueprint')), '</p>';
            },
            $this->menuPage
        );

        add_settings_field(
            'checkbox_1',
            __('Checkbox 1', 'rrze-plugin-blueprint'),
            function () {
                printf(
                    '<label><input type="checkbox" name="%1$s[checkbox_1]" id="rrze-plugin-blueprint-checkbox-1" value="1" %2$s> %3$s</label>',
                    $this->optionName,
                    checked($this->options->checkbox_1, 1, false),
                    __('Checkbox 1', 'rrze-plugin-blueprint')
                );
            },
            $this->menuPage,
            'rrze_plugin_blueprint_general_section'
        );

        add_settings_field(
            'textfield_1',
            __('Text Field 1', 'rrze-plugin-blueprint'),
            function () {
                printf(
                    '<input type="text" name="%1$s[textfield_1]" id="rrze-plugin-blueprint-textfield-1" value="%2$s" class="regular-text">',
                    $this->optionName,
                    esc_attr($this->options->textfield_1)
                );
            },
            $this->menuPage,
            'rrze_plugin_blueprint_general_section'
        );

        add_settings_field(
            'textarea_1',
            __('Text Area 1', 'rrze-plugin-blueprint'),
            function () {
                printf(
                    '<textarea name="%1$s[textarea_1]" id="rrze-plugin-blueprint-textarea-1" rows="5" class="large-text">%2$s</textarea>',
                    $this->optionName,
                    esc_textarea($this->options->textarea_1)
                );
            },
            $this->menuPage,
            'rrze_plugin_blueprint_general_section'
        );
    }

    /**
     * Sanitize and validate the plugin options.
     * 
     * This method is called when the plugin settings are saved.
     * It sanitizes the input values to ensure they are safe for storage in the database.
     * It also sets default values for any options that are not provided.
     * 
     * @param array $input The input values from the settings form.
     * @return array The sanitized and validated options.
     */
    public function sanitizeOptions($input)
    {
        // Ensure the input is an array
        if (!is_array($input)) {
            $input = [];
        }

        // Validate checkbox_1
        $input['checkbox_1'] = isset($input['checkbox_1']) ? 1 : 0;

        // Validate textfield_1
        if (isset($input['textfield_1'])) {
            $input['textfield_1'] = sanitize_text_field($input['textfield_1']);
        } else {
            $input['textfield_1'] = '';
        }

        // Validate textarea_1
        if (isset($input['textarea_1'])) {
            $input['textarea_1'] = sanitize_textarea_field($input['textarea_1']);
        } else {
            $input['textarea_1'] = '';
        }

        return $input;
    }
}
