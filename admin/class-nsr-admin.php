<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_admin {

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
	 * @var    string $plugin_domain
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
	 * The name of this view.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $view
	 */
	private $view = 'backend';

	/**
	 * The reference to the Nicescroll localisation class.
     *
     * @since  0.1.0
	 * @access private
	 * @var    object $Nicescroll_Localisation
	 */
	private $Nicescroll_Localisation;

	/**
	 * Initializes the admin part of the plugin.
	 *
	 * @since 0.1.0
	 * @param $plugin_name
	 * @param $plugin_domain
	 * @param $plugin_version
	 * @param $Loader
	 */
	public function __construct( $plugin_name, $plugin_domain, $plugin_version, $Loader ) {

		$this->plugin_name           = $plugin_name;
		$this->plugin_domain         = $plugin_domain;
		$this->plugin_version        = $plugin_version;
		$this->Loader                = $Loader;

		$this->load_dependencies();
		$this->initialize_help_tab();
		$this->initialize_settings_menu();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		// The classes that passes the settings to Nicescroll.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "includes/class-nsr-nicescroll-localisation.php";

		// The class that defines the help tab.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/includes/class-nsr-help-tab.php";

		// The classes that defines the settings menu.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/menu/class-nsr-menu.php";

		$this->Nicescroll_Localisation = new nsr_nicescroll_localisation( $this->get_plugin_name(), $this->get_plugin_domain() );
	}

	/**
	 * Registers the scripts for the admin area.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_scripts() {

		// Gets executed if Nicescroll is enabled in the frontend.
		$option = get_option( 'nicescrollr_frontend_options' );

		if ( isset( $option['frontend_enabled'] ) && $option['frontend_enabled'] ) {

			// Nicescroll library
			wp_enqueue_script(
				$this->plugin_name . '-inc-nicescroll-min-js',
				plugin_dir_url( __FILE__ ) . '../vendor/nicescroll/jquery.nicescroll.min.js',
				array( 'jquery' ),
				$this->plugin_version,
				false,
				10
			);

			// Nicescroll configuration file
			wp_enqueue_script(
				$this->plugin_name . '-nicescroll-js',
				plugin_dir_url( __FILE__ ) . '../js/nicescroll.js',
				array( 'jquery' ),
				$this->plugin_version,
				false,
				20
			);
		}
	}

	/**
	 * Creates a reference to the "help tab class" and hooks the initial function with WordPress.
	 *
	 * @since  0.1.0
	 * @see    admin/includes/class-nsr-help-tab.php
	 * @access private
	 * @return void
	 */
	private function initialize_help_tab() {

		$Help_Tab = new nsr_help_tab( $this->get_plugin_domain() );

		$this->Loader->add_action( 'in_admin_header', $Help_Tab, 'add_tab' );
	}

	/**
	 * Registers all necessary hooks and instanciates all necessary objects the settings menu is made of.
	 *
	 * @since  0.1.0
	 * @see    admin/menu/class-nsr-menu.php
	 * @access private
	 * @return void
	 */
	public function initialize_settings_menu() {

		// Creates an instance of the "settings menu class" and registers the hooks that will be executed on it.
		$Menu = new nsr_menu( $this->get_plugin_name(), $this->get_plugin_domain(), $this->get_plugin_version(), $this->get_loader() );

		$this->Loader->add_action( 'admin_enqueue_scripts', $Menu, 'enqueue_styles', 10 );
		$this->Loader->add_action( 'admin_enqueue_scripts', $Menu, 'enqueue_scripts', 20 );
		$this->Loader->add_action( 'admin_enqueue_scripts', $Menu, 'initialize_localisation', 30 );
		$this->Loader->add_action( 'admin_notices', $Menu, 'admin_notice_display' );
		$this->Loader->add_action( 'admin_menu', $Menu, 'set_section', 10 );
		$this->Loader->add_action( 'admin_menu', $Menu, 'add_options_page', 20 );
		$this->Loader->add_action( 'admin_menu', $Menu, 'initialize_settings_section', 30 );
		$this->Loader->add_action( 'wp_ajax_reset_options', $Menu, 'reset_options' );
	}

	/**
	 * Initiates localisation of the scripts.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @see    js/nicescroll.js
	 * @return void
	 */
	public function initialize_localisation() {

		// Gets executed if Nicescroll is enabled in the frontend.
		$option = get_option( 'nicescrollr_frontend_options' );

		if ( isset( $option['frontend_enabled'] ) && $option['frontend_enabled'] ) {

			$this->localize_nicescroll();
		}
	}

	/**
	 * Initiates the localisation of the frontend view.
	 *
	 * @since  0.1.0
	 * @uses   run()
	 * @see    includes/class-nsr-nicescroll-localisation.php
	 * @access private
	 * @return void
	 */
	private function localize_nicescroll() {

		$this->Nicescroll_Localisation->run( $this->view );
	}

	/**
	 * Adds support, rating, and donation links to the plugin row meta on the plugins admin screen.
	 *
	 * @since  0.1.0
	 * @param  array  $meta
	 * @param  string $file
	 * @return array  $meta
	 */
	public function add_plugin_row_meta( $meta, $file ) {

		$plugin = plugin_basename( 'nicescrollr/nsr.php' );

		if ( $file == $plugin ) {
			$meta[] = '<a href="http://demispatti.ch/plugins">' . __( 'Plugin support', $this->plugin_domain ) . '</a>';
			$meta[] = '<a href="http://wordpress.org/plugins/dp-nicescroll">' . __( 'Rate plugin', $this->plugin_domain ) . '</a>';
			$meta[] = '<a href="http://demispatti.ch/plugins">' . __( 'Donate', $this->plugin_domain ) . '</a>';
		}

		return $meta;
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
	 * Retrieves the current version number of the plugin.
	 *
	 * @since  0.1.0
	 * @return string $plugin_version
	 */
	public function get_plugin_version() {

		return $this->plugin_version;
	}

	/**
	 * Retrieves the reference to the loader class.
	 *
	 * @since  0.1.0
	 * @return object $Loader
	 */
	public function get_loader() {

		return $this->Loader;
	}

}
