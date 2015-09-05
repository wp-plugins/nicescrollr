<?php

/**
 * The class responsible for creating and displaying the help tab on this plugin's option page.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_help_tab {

	/**
	 * The name of the domain.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $plugin_domain
	 */
	private $plugin_domain;

	/**
	 * The array containing the title and the content of the help tab.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    array   $tabs
	 */
	private $tabs;

	/**
	 * Sets the content of the help tab.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function set_tab() {

		$this->tabs = array( __( 'HELP', $this->plugin_domain ) => array( 'title' => __( 'Nicescrollr help', $this->plugin_domain ) ) );
	}

	/**
	 * Sets the tab.
	 * Determines if we're on the options page,
	 * and if so, it hooks the action to load the help tab.
	 *
     * @param  $plugin_domain
	 * @return mixed | void
	 */
	public function __construct( $plugin_domain ) {

		$this->plugin_domain = $plugin_domain;

		$this->set_tab();

		// We do only add the help tab on the plugin options page.
		if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'nicescrollr_settings') {

			add_action( "load-{$GLOBALS['pagenow']}", array( $this, 'add_tabs' ), 20 );
		}
	}

	/**
	 * The callback function containing the content of the help tab.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return string  $html
	 */
	private function display_content_callback() {

		$html = '<p>' . __( "For help regarding Nicescroll itself, please refer to it's <a href='http://areaaperta.com/nicescroll/' target='_blank'>official homepage</a>.", $this->plugin_domain ) . '</p>';

		$html .= '<br />';

		$html .= '<p>' . __( "To toggle the switches, simply click on the description on it's right ( might be on bottom on mobile).", $this->plugin_domain ) . '</p>';

		$html .= '<p>' . __( "For your convenience, the backTop library is included with this plugin. If your theme already comes with this functionality, you can disable the plugin's integrated feature on the 'Plugin' settings page.", $this->plugin_domain ) . '</p>';

		$html .= '<p>' . __( "Also, there is the scrollTo library included with this plugin. This makes it easy for you to navigate to those input fields that didn't pass validation. You can disable it on the 'Plugin' settings page.", $this->plugin_domain ) . '</p>';

		$html .= '<p>' . __( "If you have any questions, comments or issues regarding this plugin, please visit the <a href='#'>plugin homepage</a>.", $this->plugin_domain ) . '</p>';

		return $html;
	}

	/**
	 * Adds the contents of the help tab to the current screen.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return mixed | void
	 */
	public function add_tab() {

		foreach ( $this->tabs as $id => $data ) {

			$title = __( $data['title'], $this->plugin_domain );

			get_current_screen()->add_help_tab( array(
				'id'       => $id,
				'title'    => __( $title, $this->plugin_domain ),
				'content'  => $this->display_content_callback(),
				'callback' => array( $this, 'prepare' )
			) );
		}
	}

	/**
	 * The callback-function that displays the contents of this help tab.
	 *
	 * @since    0.1.0
	 * @param    $screen // necessary parameter
	 * @param    $tab
	 * @return   mixed | string
	 */
	public function prepare( $screen, $tab ) {

		printf( '<p>%s</p>', $tab['callback'][0]->tabs[ $tab['id'] ]['content'] );
	}
}
