<?php

/**
 * The class that manages the settings menu.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_menu {

	/**
	 * The name of this plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $plugin_name
	 */
	private $plugin_name;

	/**
	 * The name of the domain.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string  $plugin_domain
	 */
	private $plugin_domain;

	/**
	 * The current version of the plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $plugin_version
	 */
	private $plugin_version;

	/**
	 * The reference to the loader class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $Loader
	 */
	private $Loader;

	/**
	 *  The name of the section.
	 * @since  0.1.0
	 * @access private
	 * @var    string $section
	 */
	private $section;

	/**
	 * The reference to the options class.
	 *
     * @since  0.1.0
	 * @access private
	 * @var    object $Plugin_Data
	 */
	private $Plugin_Data;

	/**
	 * The reference to the class that represents
	 * the "reset section" on the plugin options tab.
	 *
     * @since  0.1.0
	 * @access private
	 * @var    object $Reset_Section
	 */
	private $Reset_Section;

	/**
	 * The reference to the class responsible for
	 * localizing the admin part of this plugin.
     *
     * @since  0.1.0
	 * @access private
	 * @var    object $Menu_Localisation
	 */
	private $Menu_Localisation;

	/**
	 * The reference to the class responsible for
	 * the ajax functionality.
	 *
     * @since  0.1.0
	 * @access private
	 * @var    object $Ajax_Localisation
	 */
	private $Ajax_Localisation;

	/**
	 * Sets the name of the section.
	 *
	 * @since  0.1.0
	 * @uses   $_REQUEST['tab'] on page load
	 * @uses   $_REQUEST['option_page'] on "save changes"-action
	 * @return void
	 */
	public function set_section() {

		$active_tab = null;
		$section    = null;

		if ( isset( $_REQUEST['tab'] ) ) {

			$tab = $_REQUEST['tab'];

			if ( 'plugin_options' == $tab ) {

				$this->section = 'plugin';
			} else if ( 'backend_options' == $tab ) {

				$this->section = 'backend';
			} else {

				$this->section = 'frontend';
			}
		} else {

			$this->section = 'frontend';
		}

		if( isset( $_REQUEST['option_page'] ) ) {

			$option_page = $_REQUEST['option_page'];

			if ( 'nicescrollr_plugin_options' == $option_page ) {

				$this->section = 'plugin';
			} else if ( 'nicescrollr_backend_options' == $option_page ) {

				$this->section = 'backend';
			}
		}
	}

	/**
	 * Assigns the required parameters, loads its dependencies and hooks the required actions.
	 *
	 * @since  0.1.0
	 * @param  string $plugin_name
	 * @param  string $plugin_domain
	 * @param  string $plugin_version
	 * @param  object $Loader
	 * @return mixed | void
	 */
	public function __construct( $plugin_name, $plugin_domain, $plugin_version, $Loader ) {

		$this->plugin_name    = $plugin_name;
		$this->plugin_domain  = $plugin_domain;
		$this->plugin_version = $plugin_version;
		$this->Loader         = $Loader;

		$this->load_dependencies();
		$this->init();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		// The class responsible for all tasks concerning the settings api.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "menu/includes/class-nsr-settings.php";

		// The class that maintains all data like default values and their meta data.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "menu/includes/class-nsr-plugin-data.php";

		// The class that defines the section with the reset buttons.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "menu/includes/class-nsr-reset-section.php";

		// The class responsible for localizing the admin script.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "menu/includes/class-nsr-menu-localisation.php";

		// The class responsible for localizing the ajax script.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "menu/includes/class-nsr-ajax-localisation.php";

		$this->Reset_Section     = new nsr_reset_section( $this->get_plugin_domain() );
		$this->Plugin_Data       = new nsr_plugin_data( $this->get_plugin_name(), $this->get_plugin_domain() );
		$this->Menu_Localisation = new nsr_menu_localisation( $this->get_plugin_name(), $this->get_plugin_data() );
		$this->Ajax_Localisation = new nsr_ajax_localisation( $this->get_plugin_name(), $this->get_plugin_domain() );
	}

	/**
	 * Registers the st<les for the admin menu.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_styles() {

		// Color Picker.
		wp_enqueue_style( 'wp-color-picker', 10 );

		// Checkboxes.
		wp_enqueue_style(
			$this->plugin_name . '-inc-checkboxes-css',
			plugin_dir_url( __FILE__ ) . 'css/checkboxes.css',
			array(),
			$this->plugin_version,
			'all',
			20
		);

		// Fancy Select.
		wp_enqueue_style(
			$this->plugin_name . '-inc-fancy-select-css',
			plugin_dir_url( __FILE__ ) . 'css/fancyselect.css',
			array(),
			$this->plugin_version,
			'all',
			30
		);

		// Alertify core.
		wp_enqueue_style(
			$this->plugin_name . '-alertify-core-css',
			plugin_dir_url( __FILE__ ) . '../../vendor/alertify/alertify.core.css',
			array(),
			$this->plugin_version,
			'all',
			40
		);

		// Alertify custom.
		wp_enqueue_style(
			$this->plugin_name . '-alertify-custom-css',
			plugin_dir_url( __FILE__ ) . 'css/alertify.css',
			array(),
			$this->plugin_version,
			'all',
			50
		);

		// Admin.
		wp_enqueue_style(
			$this->plugin_name . '-menu-css',
			plugin_dir_url( __FILE__ ) . 'css/menu.css',
			array(),
			$this->plugin_version,
			'all',
			60
		);
	}

	/**
	 * Registers the scripts for the admin menu.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_scripts() {

		// Loads the file only if the user has the "backTop" option activated.
		$option = get_option( 'nicescrollr_plugin_options' );

		// Loads the file only if the user has the "backTop" option activated.
		if ( isset( $option['plugin_backtop_enabled'] ) && $option['plugin_backtop_enabled'] ) {

			// BackTop library.
			wp_enqueue_script(
				$this->plugin_name . '-inc-backtop-min-js',
				plugin_dir_url( __FILE__ ) . '../../vendor/backtop/jquery.backTop.min.js',
				array( 'jquery' ),
				$this->plugin_version,
				false,
				10
			);
		}

		// Loads the file only if the user has the "scrollTo" option activated.
		if ( isset( $option['plugin_scrollto_enabled'] ) && $option['plugin_scrollto_enabled'] ) {

			// ScrollTo Library.
			wp_enqueue_script(
				$this->plugin_name . '-inc-scrollto-min-js',
				plugin_dir_url( __FILE__ ) . '../../vendor/scrollto/jquery.scrollTo.min.js',
				array( 'jquery' ),
				$this->plugin_version,
				false,
				20
			);
		}

		// Loads only on the settings menu.
		if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'nicescrollr_settings' ) {

			// Color Picker.
			wp_enqueue_script( 'wp-color-picker', 30 );

			// Fancy Select.
			wp_enqueue_script(
				$this->plugin_name . '-inc-fancy-select-js',
				plugin_dir_url( __FILE__ ) . '../../vendor/fancy-select/fancySelect.js',
				array( 'jquery' ),
				$this->plugin_version,
				false,
				40
			);
		}

		// Loads only on the "plugin settings" tab.
		if( ( isset( $_REQUEST['tab'] ) && $_REQUEST['tab'] == 'plugin_options' ) && ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'nicescrollr_settings' ) ) {

			// Alertify.
			wp_enqueue_script(
				$this->plugin_name . '-inc-alertify-js',
				plugin_dir_url( __FILE__ ) . '../../vendor/alertify/alertify.js',
				array( 'jquery' ),
				$this->plugin_version,
				false,
				50
			);

			// Ajax Reset Functionality.
			wp_enqueue_script(
				$this->plugin_name . '-ajax-js',
				plugin_dir_url( __FILE__ ) . 'js/ajax.js',
				array( 'jquery', $this->plugin_name . '-inc-alertify-js' ),
				$this->plugin_version,
				false,
				60
			);
		}

		// Settings Menu.
		wp_enqueue_script(
			$this->plugin_name . '-menu-js',
			plugin_dir_url( __FILE__ ) . 'js/menu.js',
			array( 'jquery', 'wp-color-picker', $this->plugin_name . '-inc-fancy-select-js' ),
			$this->plugin_version,
			false,
			70
		);
	}

	/**
	 * Initiates localisation of the options page and the Ajax script if necessary.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function initialize_localisation() {

		$this->localize_menu();

		if ( ( isset( $_REQUEST['tab'] ) && $_REQUEST['tab'] == 'plugin_options' ) && ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'nicescrollr_settings' ) ) {

			$this->localize_ajax();
		}
	}

	/**
	 * Initiates the localisation of the admin part.
	 *
	 * @since  0.1.0
	 * @uses   run()
	 * @see    admin/menu/includes/class-nsr-menu-localisation.php
	 * @access private
	 * @return void
	 */
	private function localize_menu() {

		$this->Menu_Localisation->run();
	}

	/**
	 * Initiates the localisation of the ajax part.
	 *
	 * @since  0.1.0
	 * @uses   run()
	 * @see    admin/menu/includes/class-nsr-ajax-localisation.php
	 * @access private
	 * @return void
	 */
	private function localize_ajax() {

		$this->Ajax_Localisation->run();
	}

	/* ------------------------------------------------------------------------ *
	 * Options Menu
	 * ------------------------------------------------------------------------ */
	/**
	 * Loads the admin error notice and the components for the settings menu - if we're in the right spot.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function init() {

		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != 'nicescrollr_settings' ) {
			return;
		}

		add_action( 'admin_notices', array( &$this, 'admin_notice_display' ) );
		add_action( 'admin_menu', array( &$this, 'set_section' ), 10 );
		add_action( 'admin_menu', array( &$this, 'add_options_page' ), 20 );
		add_action( 'admin_menu', array( &$this, 'initialize_settings_section' ), 30 );
		add_action( 'wp_ajax_reset_options', array( &$this, 'reset_options' ) );
	}

	/**
	 * Creates the page title.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $plugin_name
	 */
	private function create_page_title() {

		return ucfirst( $this->plugin_name );
	}

	/**
	 * Creates the menu title.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $plugin_name
	 */
	private function create_menu_title() {

		return $this->create_page_title();
	}

	/**
	 * Creates the menu slug.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string
	 */
	private function create_menu_slug() {

		return strtolower( $this->create_page_title() ) . '_settings';
	}

	/**
	 * Registers the settings page with WordPress.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function add_options_page() {

		add_options_page(
			$this->create_menu_title(),
			$this->create_page_title(),
			'manage_options',
			$this->create_menu_slug(),
			array( &$this, 'menu_display' )
	    );
	}

	/**
	 * Initializes the components for the settings section.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function initialize_settings_section() {

		$Settings = new nsr_settings( $this->get_plugin_name(), $this->get_plugin_domain(), $this->get_section() );

		$this->Loader->add_action( 'admin_init', $Settings, 'register_settings', 1 );
		$this->Loader->add_action( 'admin_init', $Settings, 'check_for_options', 2 );
		$this->Loader->add_action( 'admin_init', $Settings, 'load_default_options', 3 );
		$this->Loader->add_action( 'admin_init', $Settings, 'initialize_options', 10 );
	}

	/**
	 * Renders the page for the menu.
	 *
	 * @since  0.1.0
	 * @uses   echo_section()
	 * @see    admin/menu/includes/class-nsr-reset-section.php
	 * @param  $active_tab
	 * @return mixed
	 */
	public function menu_display( $active_tab = '' ) {

		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<div id="icon-themes" class="icon32"></div>
			<h2><?php __( 'Nicescrollr', $this->plugin_domain ); ?></h2>

			<?php if ( isset( $_GET['tab'] ) ) {
				$active_tab = $_GET['tab'];
			} else if ( $active_tab == 'plugin_options' ) {
				$active_tab = 'plugin_options';
			} else if ( $active_tab == 'backend_options' ) {
				$active_tab = 'backend_options';
			} else {
				$active_tab = 'frontend_options';
			} ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=nicescrollr_settings&tab=frontend_options"
				   class="nav-tab <?php echo $active_tab == 'frontend_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Frontend', $this->plugin_domain ); ?></a>
				<a href="?page=nicescrollr_settings&tab=backend_options"
				   class="nav-tab <?php echo $active_tab == 'backend_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Backend', $this->plugin_domain ); ?></a>
				<a href="?page=nicescrollr_settings&tab=plugin_options"
				   class="nav-tab <?php echo $active_tab == 'plugin_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Plugin', $this->plugin_domain ); ?></a>
			</h2>

			<form id="nsr_form" method="POST" action="options.php">
				<?php
				// Submit button wrapper for styling purposes
				?>
					<div class="dp-submit-button-wrap">
				<?php

				submit_button();

				?>
					</div>
				<?php

				if ( $active_tab == 'plugin_options' ) {

					settings_fields( 'nicescrollr_plugin_options' );
					do_settings_sections( 'plugin_settings_group' );
					$this->Reset_Section->echo_section();
				} elseif ( $active_tab == 'backend_options' ) {

					settings_fields( 'nicescrollr_backend_options' );
					do_settings_sections( 'backend_settings_group' );
				} else {

					settings_fields( 'nicescrollr_frontend_options' );
					do_settings_sections( 'frontend_settings_group' );
				}

				// Do not show this button on the plugin options page, it is not needed there.
				if ( $active_tab != 'plugin_options' ) {

				// Submit button wrapper for styling purposes
				?>
					<div class="dp-submit-button-wrap">
						<p class="submit">
							<input id="submit-2" class="button button-primary" type="submit" value="<?php _e( 'Save Changes', $this->plugin_domain ) ?>" name="submit">
						</p>
					</div>
				<?php

				?>
					<!--</div>-->
				<?php
				}

				?>
			</form>

		</div><!-- /.wrap -->
	<?php
	}

	/* ------------------------------------------------------------------------ *
	 * Admin Notice
	 * ------------------------------------------------------------------------ */
	/**
	 * Displays the validation errors in the admin notice area.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @uses   get_errors_meta_data()
	 * @see    admin/menu/includes/class-nsr-plugin-data.php
	 * @return echo
	 */
	public function admin_notice_display() {

		// If there are any error-related transients
		if ( false !== get_transient( 'nicescrollr_validation_errors' ) ) {

			// Retrieves the error-array and the corresponding meta data
			$errors     = get_transient( 'nicescrollr_validation_errors' );
			$error_meta = $this->Plugin_Data->get_errors_meta_data( $errors );

			foreach ( $error_meta as $option_name => $option_meta ) {

				// Assigns the transient containing the error message to the array of notices.
				$notices[ $option_name ] = get_transient( $option_name );

				// Extracts the error message and echoes it inside the admin notice area.
				if ( is_wp_error( $notices[ $option_name ] ) ) {

					$error_message = $notices[ $option_name ]->get_error_message();

					$option = get_option( 'nicescrollr_plugin_options' );

					// scrollTo conditional
					if ( isset( $option['plugin_scrollto_enabled'] ) && $option['plugin_scrollto_enabled'] ) {

						echo "<div class='error " . $option_meta['notice_level'] . "'><a class='nsr-validation-error' href='#" . $option_name . "'>" . $option_meta['name'] . "</a><p>" . $error_message . "</p></div>";
					} else {

						echo "<div class='error " . $option_meta['notice_level'] . "'><p class='nsr-validation-error-no-scrollto' >" . $option_meta['name'] . "</p><p>" . $error_message . "</p></div>";
					}
				}
			}

			// Clean up
			delete_transient( 'nicescrollr_validation_errors' );
			$this->delete_transients( $errors );
		}
	}

	/* ------------------------------------------------------------------------ *
	 * Transients Handling
	 * ------------------------------------------------------------------------ */
	/**
	 * Deletes the temporarily stored transients for each option that didn't pass validation.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  array $errors
	 * @return void
	 */
	private function delete_transients( $errors ) {

		if ( isset( $errors ) ) {

			// Shifts the section name.
			array_shift( $errors );

			foreach ( $errors as $transient ) {

				delete_transient( $transient );
			}
		}
	}

	/* ------------------------------------------------------------------------ *
	 * Reset Section Settings Callbacks
	 * ------------------------------------------------------------------------ */
	/**
	 * Retrieves a reset-settings-request, processes it and returns the response.
	 *
	 * @hooked_action
	 *
	 * @see    admin/menu/js/ajax.js
	 * @uses   reset_settings()
	 * @see    admin/menu/includes/class-nsr-plugin-data.php
	 * @since  0.1.0
	 * @access private
	 * @return ajax response
	 */
	public function reset_options() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "reset_" . $_POST['section'] . "_nonce" ) ) {
			exit( __( "One more try and your browser will burst into flames ;-)", $this->plugin_domain ) );
		}

		if( isset( $_POST['section'] ) && $_POST['section'] != 'all' ) {

			// Resets the requested section.
			if ( true === $this->Plugin_Data->reset_settings( $_POST['section'] ) ) {

				$response = array( 'success' => __( "All done! Please refresh the page for the settings to take effect.", $this->plugin_domain ) );

				wp_send_json_success( $response );
			} else {

				$response = array( 'success' => __( "Couldn't reset the settings. Please try again.", $this->plugin_domain ) );

				wp_send_json_error( $response );
			}
		} else {

			// Resets all sections.
			if ( true === $this->Plugin_Data->reset_settings() ) {

				$response = array( 'success' => __( "All done! Please refresh the page for the settings to take effect.", $this->plugin_domain ) );

				wp_send_json_success( $response );
			} else {

				$response = array( 'success' => __( "Couldn't reset the settings. Please try again.", $this->plugin_domain ) );

				wp_send_json_error( $response );
			}
		}
	}

	/**
	 * Retrieves the name of the plugin.
	 *
	 * @since  0.1.0
	 * @return string $plugin_name
	 */
	public function get_plugin_name() {

		return $this->plugin_name;
	}

	/**
	 * Retrieves the name of the domain.
	 *
	 * @since  0.1.0
	 * @return string $plugin_domain
	 */
	public function get_plugin_domain() {

		return $this->plugin_domain;
	}

	/**
	 * Retrieves the reference to the "plugin data" class.
	 *
	 * @since  0.1.0
	 * @return object $Plugin_Data
	 */
	public function get_plugin_data() {

		return $this->Plugin_Data;
	}

	/**
	 * Retrieves the name of the section.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return string $section
	 */
	public function get_section() {

		return $this->section;
	}

}
