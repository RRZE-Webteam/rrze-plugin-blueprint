<?php

namespace RRZE\PluginBlueprint;

use RRZE\PluginBlueprint\Defaults;

use RRZE\PluginBlueprint\Common\{
    Settings\Settings,
    CPT\CPT,
    CPT\Taxonomy,
    Blocks\Blocks,
    Shortcode\Shortcode
};

defined('ABSPATH') || exit;

/**
 * Main class
 * 
 * This class serves as the entry point for the plugin.
 * It can be extended to include additional functionality or components as needed.
 * 
 * @package RRZE\PluginBlueprint
 * @since 1.0.0
 */
class Main
{
    /**
     * @var Defaults $defaults The defaults instance for the plugin.
     */
    public $defaults;

    /**
     * @var CPT $cpt The custom post type instance for the plugin.
     * 
     * This property can be used to register custom post types.
     * It can be extended or modified to register additional custom post types as needed.
     */
    public $cpt;

    /**
     * @var Taxonomy $taxonomy The taxonomy instance for the plugin.
     * 
     * This property can be used to register custom taxonomy.
     * It can be extended or modified to register additional custom taxonomy as needed.
     */
    public $taxonomy;

    /**
     * @var Settings $settings The settings instance for the plugin.
     */
    public $settings;

    /**
     * @var Blocks $blocks The blocks instance for the plugin.
     */
    public $blocks;

    /**
     * @var Shortcode $shortcode The shortcode instance for the plugin.
     * 
     * This property can be used to register custom shortcode.
     * It can be extended or modified to register additional shortcode as needed.
     */
    public $shortcode;

    /**
     * Constructor for the Main class.
     * 
     * The constructor sets up the plugin's defaults, custom post types, taxonomies, settings,
     * shortcodes, and blocks. It can be extended or modified to include additional functionality
     * or components as needed.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->defaults();

        $this->cpt();

        $this->taxonomy();

        $this->settings();

        $this->shortcode();

        $this->blocks();

        // Initialize other components or functionality as needed.   
    }

    /**
     * Set up defaults for the plugin.
     * 
     * This method initializes the Defaults class, which contains default values for the plugin.
     * It can be extended or modified to include additional default values as needed.
     * 
     * @return void
     */
    public function defaults()
    {
        $this->defaults = new Defaults();
    }

    /**
     * Register custom post type.
     * 
     * This method registers a custom post type using the CPT class.
     * It can be extended or modified to register additional custom post types as needed.
     * 
     * @return void
     */
    public function cpt()
    {
        // Example of registering a custom post type
        // This can be extended or modified as needed.
        $this->cpt = new CPT($this->defaults->get('cpt')['name']);

        add_action(
            'init',
            fn() =>
            $this->cpt->register([
                'labels' => [
                    'name'               => __('Books', 'textdomain'),
                    'singular_name'      => __('Book', 'textdomain'),
                    'add_new'            => __('Add Book', 'textdomain'),
                    'add_new_item'       => __('Add New Book', 'textdomain'),
                    'edit_item'          => __('Edit Book', 'textdomain'),
                    'new_item'           => __('New Book', 'textdomain'),
                    'view_item'          => __('View Book', 'textdomain'),
                    'search_items'       => __('Search Books', 'textdomain'),
                    'not_found'          => __('No books found', 'textdomain'),
                    'not_found_in_trash' => __('No books found in Trash', 'textdomain'),
                ],
                'public' => true,
                'has_archive' => true,
                'show_in_rest' => true,
                'supports' => ['title', 'editor', 'thumbnail'],
                'menu_icon' => 'dashicons-book-alt',
            ])
        );
    }

    /**
     * Register custom taxonomy.
     * 
     * This method registers a custom taxonomy using the Taxonomy class.
     * It can be extended or modified to register additional custom taxonomy as needed.
     *
     * @return void
     */
    public function taxonomy()
    {
        // Example of registering a custom taxonomy.
        $this->taxonomy = new Taxonomy(
            $this->defaults->get('cpt')['taxonomy_name'],
            $this->defaults->get('cpt')['name']
        );

        add_action(
            'init',
            fn() =>
            $this->taxonomy->register(
                [
                    'labels' => [
                        'name' => __('Genres', 'rrze-plugin-blueprint'),
                        'singular_name' => __('Genre', 'rrze-plugin-blueprint'),
                    ],
                    'public' => true,
                    'hierarchical' => true,
                    'show_in_rest' => true,
                ]
            )
        );
    }

    /**
     * Shortcode method
     * 
     * This method registers a shortcode using the Shortcode class.
     * It can be extended or modified to register additional shortcode as needed.
     * 
     * @return void
     */
    public function shortcode()
    {
        // Example of registering a shortcode.
        $this->shortcode = new Shortcode('example_shortcode');

        add_action(
            'init',
            fn() =>
            $this->shortcode->register(
                function ($atts, $content = null, $tag = '') {
                    $atts = shortcode_atts(
                        ['title' => 'Default Title'],
                        $atts,
                        $tag
                    );
                    return '<div class="example-shortcode">' . esc_html($atts['title']) . '</div>';
                }
            )
        );
    }

    /**
     * Blocks method
     * 
     * This method registers custom blocks using the Blocks class.
     * It can be extended or modified to register additional blocks as needed.
     * 
     * @return void
     */
    public function blocks()
    {
        // Example of registering custom blocks.
        $this->blocks = new Blocks(
            [                                  // Array of block names
                'block-static',
                'block-dynamic'
            ],
            plugin()->getPath('build/blocks'), // Blocks directory path
            plugin()->getPath()                // Plugin directory path
        );
    }

    /**
     * Settings method
     * 
     * This method sets up the plugin settings using the Settings class.
     * It defines the settings sections and options that will be available in the WordPress admin area
     * and provides validation and sanitization for the settings.
     * 
     * @return void
     */
    public function settings()
    {
        // Example of setting up plugin settings.
        add_action(
            'init',
            fn() =>
            $this->settings = new Settings(__('RRZE Plugin Blueprint Settings', 'rrze-plugin-blueprint'))
        );

        add_action('init', function () {
            $this->settings = new Settings(__('RRZE Plugin Blueprint Settings', 'rrze-plugin-blueprint'));
            $this->settings->setCapability($this->defaults->get('settings')['capability'])
                ->setOptionName($this->defaults->get('settings')['option_name'])
                ->setMenuTitle(__('Plugin Blueprint', 'rrze-plugin-blueprint'))
                ->setMenuPosition(6)
                ->setMenuParentSlug('options-general.php');

            $sectionGeneral = $this->settings->addSection(__('General', 'rrze-plugin-blueprint'));

            $sectionGeneral->addOption('checkbox', [
                'name' => 'checkbox_option',
                'label' => __('Checkbox Option', 'rrze-plugin-blueprint'),
                'description' => __('Check this option to enable the feature.', 'rrze-plugin-blueprint'),
                'default' => false,
            ]);

            $sectionGeneral->addOption('text', [
                'name' => 'text_option',
                'label' => __('Text Option', 'rrze-plugin-blueprint'),
                'description' => __('Enter some text.', 'rrze-plugin-blueprint'),
                'default' => __('Enter your text here...', 'rrze-plugin-blueprint'),
                'sanitize' => 'sanitize_text_field'
            ]);

            $sectionGeneral->addOption('text', [
                'name' => 'slug_option',
                'label' => __('Slug Option', 'rrze-plugin-blueprint'),
                'description' => __('Enter a slug.', 'rrze-plugin-blueprint'),
                'default' => '',
                'sanitize' => 'sanitize_title',
                'validate' => [
                    [
                        'feedback' => __('The slug can have between 4 and 32 alphanumeric characters.', 'rrze-plugin-blueprint'),
                        'callback' => fn($value) => mb_strlen(sanitize_title($value)) >= 4 && mb_strlen(sanitize_title($value)) <= 32
                    ]
                ]
            ]);

            $sectionGeneral->addOption('select', [
                'name' => 'select_option',
                'label' => __('Select Option', 'rrze-plugin-blueprint'),
                'description' => __('Select an option from the dropdown.', 'rrze-plugin-blueprint'),
                'options' => [
                    'none'  => __('None', 'rrze-plugin-blueprint'),
                    'one'   => __('One', 'rrze-plugin-blueprint'),
                    'two'   => __('Two', 'rrze-plugin-blueprint'),
                    'three' => __('Three', 'rrze-plugin-blueprint')
                ],
                'default' => 'none',
                'sanitize' => 'sanitize_text_field',
                'validate' => [
                    [
                        'feedback' => __('Please select a valid option.', 'rrze-plugin-blueprint'),
                        'callback' => fn($value) => in_array($value, ['none', 'one', 'two', 'three'], true)
                    ]
                ]
            ]);
        });

        add_action('init', fn() => $this->settings->build());

        add_action('admin_init', fn() => $this->settings->save(), 20);
        add_action('admin_menu', fn() => $this->settings->addToMenu(), 20);
        add_action('admin_head', fn() => $this->settings->styling(), 20);
    }
}
