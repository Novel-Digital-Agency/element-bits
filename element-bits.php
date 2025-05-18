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
        
        // Include carousel module
        if ( in_array( 'eb-carousel', self::get_active_modules() ) ) {
            require_once ELEBITS_PATH . 'widgets/eb-carousel/module.php';
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
        include_once ELEBITS_PATH . 'inc/functions.php';    
        
        // Register Elementor category
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_categories' ] );
        
        // Register Google Maps API if key exists
        add_action( 'wp_enqueue_scripts', [ $this, 'register_external_scripts' ] );
    }
    
    /**
     * Register external scripts like Google Maps API.
     */
    public function register_external_scripts() {
        // Get active modules
        $active_modules = self::get_active_modules();
        
        // Register Swiper for Carousel
        if ( in_array( 'eb-carousel', $active_modules ) ) {
            // Swiper CSS
            wp_register_style(
                'swiper',
                'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css',
                [],
                '8.4.7'
            );
            
            // Swiper JS
            wp_register_script(
                'swiper',
                'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js',
                [ 'jquery' ],
                '8.4.7',
                true
            );
        }
        
        // Register Google Maps API if key exists and module is active
        $gmap_api_key = get_option( 'element_bits_gmap_key', '' );
        if ( !empty( $gmap_api_key ) && in_array( 'eb-google-map', $active_modules ) ) {
            $maps_params['key'] = $gmap_api_key;
            $maps_params['libraries'] = 'marker';

            $google_maps_lib_url = add_query_arg( $maps_params, 'https://maps.googleapis.com/maps/api/js' );
            
            wp_register_script(
                'eb-google-maps-lib',
                $google_maps_lib_url,
                [],
                wp_rand(),
                true
            );
        }

        // Register Alpine.js if needed
        if ( 1==1 ) {
            wp_register_script(
                'alpinejs',
                'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js',
                [],
                wp_rand(),
                [
                    'strategy'  => 'defer',
                    'in_footer' => false,
                ]
            );
        }
    }

    public static function get_active_modules() {
        return get_option( 'element_bits_active_modules', [] );
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
        $modules = elebits_get_modules();
        $active_modules = self::get_active_modules();
        
        foreach ( $modules as $module_name => $module ) {
            // Skip hidden modules or inactive modules
            if ( $module['hidden'] || !in_array($module_name, $active_modules, true) ) {
                continue;
            }

            // Handle carousel widget dependencies
            if ( 'eb-carousel' === $module_name ) {
                // Enqueue Swiper CSS
                wp_enqueue_style('swiper');
                
                // Register and enqueue carousel script with Swiper as dependency
                wp_register_script(
                    sanitize_title($module['name']),
                    $module['script_url'],
                    ['jquery', 'elementor-frontend', 'swiper'],
                    wp_rand(),
                    true
                );
                
                // Register and enqueue carousel styles
                wp_register_style(
                    sanitize_title($module['name']),
                    $module['style_url'],
                    ['swiper'],
                    wp_rand()
                );
                
                wp_enqueue_style(sanitize_title($module['name']));
                wp_enqueue_script(sanitize_title($module['name']));
                
                continue;
            }
            
            // Register common assets for other widgets
            if ( $module['style_url'] ) {
                wp_register_style(
                    sanitize_title($module['name']),
                    $module['style_url'],
                    ['elementor-frontend'],
                    wp_rand()
                );
            }

            if ( $module['script_url'] ) {
                wp_register_script(
                    sanitize_title($module['name']),
                    $module['script_url'],
                    ['jquery', 'elementor-frontend'],
                    wp_rand(),
                    true
                );
            }

            require_once $module['widget_file_path'];
            $widgets_manager->register( new $module['class_name']() );
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
