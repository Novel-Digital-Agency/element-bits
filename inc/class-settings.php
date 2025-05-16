<?php
/**
 * Plugin Settings
 *
 * @package Element_Bits
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Handles all plugin settings functionality.
 */
class Elebits_Settings {

	/**
	 * Instance of this class.
	 *
	 * @var Elebits_Settings
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance of this class.
	 *
	 * @return Elebits_Settings
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize the settings.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'settings_init' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Add admin menu item.
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Element Bits', 'element-bits' ),
			__( 'Element Bits', 'element-bits' ),
			'manage_options',
			'element-bits',
			[ $this, 'settings_page' ],
			'dashicons-admin-generic',
			58
		);
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @param string $hook The current admin page.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'toplevel_page_element-bits' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'element-bits-admin',
			ELEBITS_URL . 'assets/css/admin.css',
			[],
			ELEBITS_VERSION
		);
	}

	/**
	 * Initialize settings.
	 */
	public function settings_init() {
		// Register settings
		register_setting( 
			'element_bits_settings', 
			'element_bits_active_modules',
			[
				'type' => 'array',
				'sanitize_callback' => [ $this, 'sanitize_active_modules' ],
				'default' => [],
			]
		);
		
		// Register Google Maps API Key setting
		register_setting(
			'element_bits_settings',
			'element_bits_gmap_key',
			[
				'type' => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default' => '',
			]
		);

		// Add sections here if needed
	}

	/**
	 * Sanitize active modules.
	 *
	 * @param array $input The input array to sanitize.
	 * @return array
	 */
	public function sanitize_active_modules( $input ) {
		$sanitized_input = [];
		
		if ( is_array( $input ) ) {
			foreach ( $input as $module_name ) {
				$sanitized_input[] = sanitize_text_field( $module_name );
			}
		}
		
		return $sanitized_input;
	}

	/**
	 * Settings page content.
	 */
	public function settings_page() {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Get active tab
		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'configuration';
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<h2 class="nav-tab-wrapper">
				<a href="?page=element-bits&tab=configuration" class="nav-tab <?php echo $active_tab === 'configuration' ? 'nav-tab-active' : ''; ?>">
					<?php esc_html_e( 'Configuration', 'element-bits' ); ?>
				</a>
				<a href="?page=element-bits&tab=widgets" class="nav-tab <?php echo $active_tab === 'widgets' ? 'nav-tab-active' : ''; ?>">
					<?php esc_html_e( 'Widgets', 'element-bits' ); ?>
				</a>
			</h2>

			<div class="tab-content">
				<?php
				switch ( $active_tab ) {
					case 'configuration':
						$this->render_configuration_tab();
						break;
					case 'widgets':
						$this->render_widgets_tab();
						break;
					default:
						$this->render_configuration_tab();
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the configuration tab content.
	 */
	private function render_configuration_tab() {
		?>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'element_bits_settings' );
			do_settings_sections( 'element_bits_settings' );
			submit_button( __( 'Save Settings', 'element-bits' ) );
			?>
		</form>
		<?php
	}

	/**
	 * Render the widgets tab content.
	 */
	private function render_widgets_tab() {
		$modules = elebits_get_modules();
		$active_modules = get_option( 'element_bits_active_modules', [] );
		
		?>
		<div class="element-bits-widgets">
			<h2><?php esc_html_e( 'Available Widgets', 'element-bits' ); ?></h2>
			<p><?php esc_html_e( 'Enable or disable Element Bits widgets using the checkboxes below.', 'element-bits' ); ?></p>
			
			<form method="post" action="options.php" class="elebits-modules-form">
				<?php settings_fields( 'element_bits_settings' ); ?>
				
				<div class="elebits-modules-grid">
					<?php foreach ( $modules as $module_name => $module ) : ?>
						<div class="elebits-module-card">
							<div class="elebits-module-header">
								<div class="elebits-module-icon">
									<i class="<?php echo esc_attr( $module['icon'] ); ?>"></i>
								</div>
								<div class="elebits-module-title">
									<h3><?php echo esc_html( $module['title'] ); ?></h3>
								</div>
								<div class="elebits-module-toggle">
									<label class="elebits-switch">
										<input 
											type="checkbox" 
											name="element_bits_active_modules[]" 
											value="<?php echo esc_attr( $module_name ); ?>"
											<?php checked( in_array( $module_name, $active_modules, true ) ); ?>
										>
										<span class="elebits-slider"></span>
									</label>
								</div>
							</div>
							<div class="elebits-module-description">
								<p><?php echo isset( $module['description'] ) ? esc_html( $module['description'] ) : esc_html__( 'Elementor widget by Element Bits', 'element-bits' ); ?></p>
								
								<?php if ( $module_name === 'eb-google-map' ) : ?>
									<div class="elebits-module-api-key">
										<p class="elebits-api-key-desc">
											<strong><?php esc_html_e( 'Google Maps API Key', 'element-bits' ); ?></strong><br>
											<?php esc_html_e( 'Enter your Google Maps API Key below. This is necessary for the map widget to function properly. For instructions on obtaining an API key, please refer to our documentation.', 'element-bits' ); ?>
										</p>
										<input 
											type="text" 
											class="elebits-api-key-input" 
											name="element_bits_gmap_key" 
											value="<?php echo esc_attr( get_option( 'element_bits_gmap_key', '' ) ); ?>"
											placeholder="<?php esc_attr_e( 'Enter your Google Maps API Key', 'element-bits' ); ?>"
										>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				
				<?php submit_button( __( 'Save Changes', 'element-bits' ) ); ?>
			</form>
		</div>
		<?php
	}
}
