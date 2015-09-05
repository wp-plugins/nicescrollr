<?php

/**
 * The file that defines the core plugin class.
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
class nsr {

	/**
	 * The name of the plugin.
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string  $plugin_name
	 */
	protected $plugin_name;

	/**
	 * The name of the domain.
	 *
	 * @since  0.1.0
	 * @access protected
	 * @var    string  $plugin_domain
	 */
	protected $plugin_domain;

	/**
	 * The current version number of the plugin.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string  $plugin_version
	 */
	private $plugin_version;

	/**
	 * The reference to the loader class.
	 *
	 * @since 0.1.0
	 * @acces private
	 * @var   object  $Loader
	 */
	private $Loader;

	/**
	 * Defines the locale for this plugin.
	 *
	 * @since  0.1.0
	 * @uses   set_domain()
	 * @see    includes/class-nsr-i18n.php
	 * @access private
	 */
	private function set_locale() {

		$Plugin_i18n = new nsr_i18n();
		$Plugin_i18n->set_domain( $this->get_plugin_domain() );

		$this->Loader->add_action( 'plugins_loaded', $Plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Defines the core functionality of the plugin.
	 *
	 * @since  0.1.0
	 * @return mixed / void
	 */
	public function __construct() {

		$this->plugin_name    = 'nicescrollr';
		$this->plugin_version = '0.1.0';
		$this->plugin_domain  = $this->get_plugin_name();

		$this->load_dependencies();
		$this->set_locale();

		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Loads it's dependencies.
	 *
	 * @since  0.1.0
	 * @access private
	 */
	private function load_dependencies() {

		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-loader.php';

		// The class responsible for defining internationalization functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nsr-i18n.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-nsr-admin.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-nsr-public.php';

		// The references
		$this->Loader    = new nsr_loader();
	}

	/**
	 * Creates an instance and registers all hooks related to the admin part.
	 *
	 * @since  0.1.0
	 * @see    admin/class-nsr-admin.php
	 * @access private
	 */
	private function define_admin_hooks() {

		$Admin = new nsr_admin( $this->get_plugin_name(), $this->get_plugin_domain(), $this->get_plugin_version(), $this->get_loader() );

		$this->Loader->add_action( 'admin_enqueue_scripts', $Admin, 'enqueue_scripts', 20 );
		$this->Loader->add_action( 'admin_enqueue_scripts', $Admin, 'initialize_localisation', 30 );
		//$this->Loader->add_action( 'plugin_row_meta', $admin, 'plugin_row_meta', 10, 2 );
	}

	/**
	 * Creates an instance and registers all hooks related to the public part.
	 *
     * @since  0.1.0
	 * @see    public/class-nsr-public.php
	 * @access private
	 */
	private function define_public_hooks() {

		$Public = new nsr_public( $this->get_plugin_name(), $this->get_plugin_domain(), $this->get_plugin_version() );

		$this->Loader->add_action( 'wp_enqueue_scripts', $Public, 'enqueue_scripts', 10 );
		$this->Loader->add_action( 'wp_enqueue_scripts', $Public, 'initialize_localisation', 20 );
	}

	/**
	 * Runs the loader to execute all registered hooks with WordPress.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function run() {

		$this->Loader->run();
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
	 * @return string
	 */
	public function get_plugin_domain() {

		return $this->get_plugin_name();
	}

	/**
	 * Retrieves the current version number.
	 *
	 * @since  0.1.0
	 * @return string
	 */
	public function get_plugin_version() {

		return $this->plugin_version;
	}

	/**
	 * Retrieves the reference to the loader class.
	 *
	 * @since  0.1.0
	 * @return object
	 */
	public function get_loader() {

		return $this->Loader;
	}

}
