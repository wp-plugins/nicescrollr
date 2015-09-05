<?php

/**
 * The class responsible for localizing the Nicescroll configuration file.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_nicescroll_localisation {

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
	 * The reference to the options class.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    object $Plugin_Data
	 */
	private $Plugin_Data;

	/**
	 * The name of the view for the admin area.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $view
	 */
	public $view;

	/**
	 * @param string $plugin_name
	 * @param string $plugin_domain
	 */
	public function __construct( $plugin_name, $plugin_domain ) {

		$this->plugin_name   = $plugin_name;
		$this->plugin_domain = $plugin_domain;

		$this->load_dependency();
	}

	private function load_dependency() {

		// The class that maintains all data like default values and their meta data.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/menu/includes/class-nsr-plugin-data.php";

		$this->Plugin_Data = new nsr_plugin_data( $this->get_plugin_name(), $this->get_plugin_domain() );
	}

	/**
	 * Calls the function that passes the parameters to the Nicescroll library.
	 *
	 * @since  0.1.0
	 * @param  string $view
	 * @return void
	 */
	public function run( $view ) {

		$this->localize_nicescroll( $view );
	}

	/**
	 * @since  0.1.0
	 * @param  string $view
	 * @return void
	 */
	private function localize_nicescroll( $view ) {

		wp_localize_script( $this->plugin_name . '-nicescroll-js', 'GlobalOptions', $this->get_nicescroll_configuration( $view ) );
	}

	/**
	 * Retrieves the options per requested view from the database
	 * and removes the determined prefix so that
	 * the option keys correspond to the naming conventions of the Nicescroll library.
	 * it contains a fallback to prevent "undefined"-errors in the script that's to be localized.
	 *
	 * @since  0.1.0
	 * @param  string $view
	 * @return array
	 */
	public function get_nicescroll_configuration( $view ) {

		if( false !== get_option( $this->plugin_name . '_' . $view . '_options' ) ) {

			$options = get_option( $this->plugin_name . '_' . $view . '_options' );
		} else {

			$options = $this->Plugin_Data->get_default_options( $view );
		}

		$configuration = [ ];

		// Removes the first entry, since it is not a Nicescroll configuration parameter.
		array_shift( $options );

		// Removes the prefixed name of the view.
		foreach ( $options as $option_name => $value ) {

			$option_name = str_replace( $view . '_', '', $option_name );

			$configuration[ $option_name ] = $value;
		}

		return $configuration;
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

}
