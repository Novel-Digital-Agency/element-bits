<?php
/**
 * Plugin Name: Element Bits
 * Plugin URI: https://noveldigital.pro/plugins/element-bits
 * Description: Custom Elementor widgets and extensions.
 * Version: 1.0.0
 * Author: noveldigital.pro
 * Author URI: https://noveldigital.pro
 * Text Domain: element-bits
 * Domain Path: /languages
 * Requires at least: 5.6
 * Requires PHP: 7.4
 * Elementor tested up to: 3.20.0
 * Elementor Pro tested up to: 3.20.0
 *
 * @package Element_Bits
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define plugin constants
define( 'ELEBITS_VERSION', '1.0.0' );
define( 'ELEBITS_FILE', __FILE__ );
define( 'ELEBITS_PATH', plugin_dir_path( ELEBITS_FILE ) );
define( 'ELEBITS_URL', plugin_dir_url( ELEBITS_FILE ) );
define( 'ELEBITS_BASENAME', plugin_basename( ELEBITS_FILE ) );

/**
 * The core plugin class.
 */
final class Element_Bits {

    /**
     * Instance of this class.
     *
     * @var Element_Bits
     */
    /**
     * Instance of this class.
     *
     * @var Element_Bits
     */
    private static $instance = null;

    /**
     * Get the singleton instance of this class.
     *
     * @return Element_Bits
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize the plugin.
     */
    public function __construct() {
        $this->init_hooks();
        $this->init_components();
    }

    /**
     * Initialize plugin components.
     */
    private function init_components() {
        // Initialize settings
        if ( is_admin() ) {
            require_once ELEBITS_PATH . 'inc/class-settings.php';
            $this->settings = Elebits_Settings::instance();
        }
    }

    /**
     * Initialize hooks.
     */
    private function init_hooks() {
        // Register activation and deactivation hooks
        register_activation_hook( ELEBITS_FILE, [ $this, 'activate' ] );
        register_deactivation_hook( ELEBITS_FILE, [ $this, 'deactivate' ] );
        
        // Initialize the plugin after all plugins are loaded
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        
        // Load text domain
        add_action( 'init', [ $this, 'load_textdomain' ] );
        
        // Register Elementor widgets
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
    }
    
    /**
     * Load plugin textdomain.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'element-bits', false, dirname( plugin_basename( ELEBITS_FILE ) ) . '/languages' );
    }

    /**
     * Check if Elementor is installed and activated.
     *
     * @return bool
     */
    public function is_elementor_installed() {
        return did_action( 'elementor/loaded' );
    }

    /**
     * Check if Elementor Pro is installed and activated.
     *
     * @return bool
     */
    public function is_elementor_pro_installed() {
        return function_exists( 'elementor_pro_load_plugin' );
    }

    /**
     * Check if all required plugins are active.
     *
     * @return bool
     */
    public function check_requirements() {
        $missing = [];

        if ( ! $this->is_elementor_installed() ) {
            $missing[] = 'Elementor';
        }

        if ( ! $this->is_elementor_pro_installed() ) {
            $missing[] = 'Elementor Pro';
        }

        if ( ! empty( $missing ) ) {
            add_action( 'admin_notices', function() use ( $missing ) {
                $message = sprintf(
                    /* translators: 1: Plugin Name 2: List of missing plugins */
                    __( '%1$s requires the following plugins to be installed and activated: %2$s', 'element-bits' ),
                    '<strong>' . __( 'Element Bits', 'element-bits' ) . '</strong>',
                    '<strong>' . implode( '</strong>, <strong>', $missing ) . '</strong>'
                );
                ?>
                <div class="notice notice-error">
                    <p><?php echo wp_kses_post( $message ); ?></p>
                </div>
                <?php
            } );
            return false;
        }

        return true;
    }

    /**
     * Initialize the plugin.
     */
    public function init() {
        // Check if required plugins are active
        if ( ! $this->check_requirements() ) {
            return;
        }

        // Initialize plugin functionality
        $this->load_dependencies();
    }

    /**
     * Load required dependencies.
     */
    /**
     * Load required dependencies.
     */
    private function load_dependencies() {
        // Register widget assets
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_widget_assets' ] );
        
        // Register Elementor category
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_categories' ] );
    }
    
    /**
     * Register widget assets (scripts and styles).
     */
    public function register_widget_assets() {
        // Widgets directory
        $widgets_dir = ELEBITS_PATH . 'widgets/';
        
        // Check if the directory exists
        if ( ! file_exists( $widgets_dir ) ) {
            return;
        }
        
        // Get all widget directories
        $widgets = glob( $widgets_dir . '*', GLOB_ONLYDIR );
        
        foreach ( $widgets as $widget_dir ) {
            $widget_name = basename( $widget_dir );
            $module_file = $widget_dir . '/module.php';
            
            // Check if module file exists
            if ( file_exists( $module_file ) ) {
                $module = include $module_file;
                
                if ( ! empty( $module['script_handles'] ) ) {
                    foreach ( $module['script_handles'] as $handle ) {
                        $js_file = ELEBITS_URL . 'widgets/' . $widget_name . '/widget.js';
                        if ( file_exists( ELEBITS_PATH . 'widgets/' . $widget_name . '/widget.js' ) ) {
                            wp_register_script(
                                $handle,
                                $js_file,
                                [ 'jquery', 'elementor-frontend' ],
                                ELEBITS_VERSION,
                                true
                            );
                        }
                    }
                }
                
                if ( ! empty( $module['style_handles'] ) ) {
                    foreach ( $module['style_handles'] as $handle ) {
                        $css_file = ELEBITS_URL . 'widgets/' . $widget_name . '/widget.css';
                        if ( file_exists( ELEBITS_PATH . 'widgets/' . $widget_name . '/widget.css' ) ) {
                            wp_register_style(
                                $handle,
                                $css_file,
                                [],
                                ELEBITS_VERSION
                            );
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Register widget categories.
     *
     * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
     */
    public function register_widget_categories( $elements_manager ) {
        $elements_manager->add_category(
            'element-bits',
            [
                'title' => esc_html__( 'Element Bits', 'element-bits' ),
                'icon'  => 'eicon-elementor',
            ]
        );
    }
    
    /**
     * Register Elementor widgets.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register_widgets( $widgets_manager ) {
        // Widgets directory
        $widgets_dir = ELEBITS_PATH . 'widgets/';
        
        // Check if the directory exists
        if ( ! file_exists( $widgets_dir ) ) {
            return;
        }
        
        // Get all widget directories
        $widgets = glob( $widgets_dir . '*', GLOB_ONLYDIR );
        
        foreach ( $widgets as $widget_dir ) {
            $widget_file = $widget_dir . '/widget.php';
            
            // Include widget file if it exists
            if ( file_exists( $widget_file ) ) {
                require_once $widget_file;
                
                // Get the widget class name from the filename
                $widget_name = basename( $widget_dir );
                $class_name = '\Element_Bits\Widgets\EB_' . str_replace('-', '_', ucfirst( $widget_name ) );
                
                // Register the widget if the class exists
                if ( class_exists( $class_name ) ) {
                    $widgets_manager->register( new $class_name() );
                }
            }
        }
    }

    /**
     * Instance of the settings class.
     *
     * @var Elebits_Settings
     */
    public $settings;

    /**
     * Run the plugin.
     */
    public function run() {
        // Main plugin functionality will be executed here
    }

    /**
     * Plugin activation.
     */
    public static function activate() {
        // Activation code here
    }

    /**
     * Plugin deactivation.
     */
    public static function deactivate() {
        // Deactivation code here
    }
}

/**
 * Main instance of Element_Bits.
 *
 * @return Element_Bits The main plugin instance.
 */
function elebits() {
    return Element_Bits::instance();
}

// Initialize the plugin.
elebits();
