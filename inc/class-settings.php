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
	 * Initialize settings.
	 */
	public function settings_init() {
		// Register settings
		register_setting( 'element_bits_settings', 'element_bits_options' );

		// Add sections here if needed
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
		?>
		<div class="element-bits-widgets">
			<p><?php esc_html_e( 'Configure your widgets here.', 'element-bits' ); ?></p>
		</div>
		<?php
	}
}
