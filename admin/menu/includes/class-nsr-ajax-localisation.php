<?php

/**
 * The class responsible for localizing the ajax part of this plugin.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_ajax_localisation {

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
	 * Assigns the required parameters to its instance.
	 *
	 * @since 0.1.0
	 * @param $plugin_name
	 * @param $plugin_domain
	 */
	public function __construct( $plugin_name, $plugin_domain ) {

		$this->plugin_name   = $plugin_name;
		$this->plugin_domain = $plugin_domain;
	}

	/**
	 * Kicks off localisation of the ajax part of this plugin.
	 *
	 * @since  0.1.0
     * @return void
	 */
	public function run() {

		$this->localize_script();
	}

	/**
	 * Retrieves the confirmation texts for the ajax script and passes them to it.
	 *
	 * @since  0.1.0
	 * @see    admin/menu/js/ajax.js
	 * @access private
	 * @return void
	 */
	private function localize_script() {

		wp_localize_script( $this->plugin_name . '-ajax-js', 'Ajax', array_merge( $this->get_confirmation_texts(), $this->get_confirmation_dialog_labels() ) );
	}

	/**
	 * Returns the string that shows up when the user wants to reset the frontend options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string $confirm_reset
	 */
	private function get_reset_frontend_confirmation_text() {

		$confirm_reset = __( "This resets the frontend view to it's defaults. Continue?", $this->plugin_domain );

		return $confirm_reset;
	}

	/**
	 * Returns the string that shows up when the user wants to reset the backend options.
	 *
     * @since  0.1.0
	 * @access private
	 * @return string $confirm_reset
	 */
	private function get_reset_backend_confirmation_text() {

		$confirm_reset = __( "This resets the backend view to it's defaults. Continue?", $this->plugin_domain );

		return $confirm_reset;
	}

	/**
	 * Returns the string that shows up when the user wants to reset the plugin options.
	 *
     * @since  0.1.0
	 * @access private
	 * @return string $confirm_reset
	 */
	private function get_reset_plugin_confirmation_text() {

		$confirm_reset = __( "This resets the plugin to it's defaults. Continue?", $this->plugin_domain );

		return $confirm_reset;
	}

	/**
	 * Returns the string that shows up when the user wants to reset all options.
	 *
     * @since  0.1.0
	 * @access private
	 * @return string $confirm_reset
	 */
	private function get_reset_all_confirmation_text() {

		$confirm_reset = __( "This resets all Nicescrollr settings to their defaults. Continue?", $this->plugin_domain );

		return $confirm_reset;
	}

	/**
	 * Returns the array containing the preceeding confirmation texts.
	 *
	 * @since    0.1.0
	 * @return   array $localisation_data
	 */
	public function get_confirmation_texts() {

		$localisation_data = array(
			'reset_frontend_confirmation' => $this->get_reset_frontend_confirmation_text(),
			'reset_backend_confirmation'  => $this->get_reset_backend_confirmation_text(),
			'reset_plugin_confirmation'   => $this->get_reset_plugin_confirmation_text(),
			'reset_all_confirmation'      => $this->get_reset_all_confirmation_text(),
		);

		return $localisation_data;
	}

	/**
	 * Returns an array containing the localized labels for the confirmation dialog.
	 *
	 * @since    0.1.0
	 * @return   array $labels
	 */
	public function get_confirmation_dialog_labels() {

		$labels = array(
			'oki_doki'    => __( 'ok', $this->plugin_domain ),
			'no_way_jose' => __( 'cancel', $this->plugin_domain )
		);

		return $labels;
	}
}
