<?php

/**
 * The public-specific functionality of the plugin.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/public
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_public {

	/**
	 * The name of this plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string  $plugin_name
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
	 * @var    string  $plugin_version
	 */
	private $plugin_version;

	/**
	 * The name of this view.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $view
	 */
	private $view = 'frontend';

	/**
	 * The reference to the localisation class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $Nicescroll_Localizer
	 */
	private $Nicescroll_Localizer;

	/**
	 * Initializes the public part of the plugin.
	 *
	 * @since 0.1.0
     * @param string $plugin_name
	 * @param string $plugin_domain
	 * @param string $plugin_version
	 */
	public function __construct( $plugin_name, $plugin_domain, $plugin_version ) {

		$this->plugin_name    = $plugin_name;
		$this->plugin_domain  = $plugin_domain;
		$this->plugin_version = $plugin_version;

		$this->load_dependencies();
	}

	/**
	 * Loads it's dependencies.
	 *
     * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function load_dependencies() {

		// The class responsible for passing the configuration to this plugin's instance of Nicescroll.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-nicescroll-localisation.php';

		$this->Nicescroll_Localizer = new nsr_nicescroll_localisation( $this->get_plugin_name(), $this->get_plugin_domain() );
	}

	/**
	 * Registers the scripts for the public area.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function enqueue_scripts() {

		$option = get_option( 'nicescrollr_frontend_options' );

		// We only enqueue these scripts if Nicescroll is enabled in the frontend.
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

			// The file containing our instance of Nicescroll.
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
	 * Initiates localisation of the frontend view.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function initialize_localisation() {

		// Gets executed if Nicescroll is enabled in the frontend.
		$option = get_option( 'nicescrollr_frontend_options' );

		if ( isset( $option['frontend_enabled'] ) && $option['frontend_enabled'] ) {

			$this->localize_view();
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
	private function localize_view() {

		$this->Nicescroll_Localizer->run( $this->view );
	}

	/**
	 * Retrieves the name of the plugin.
	 *
	 * @since  0.1.0
	 * @return string
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

}
