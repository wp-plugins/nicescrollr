<?php

/**
 * The class that maintains the default options and the related meta data.
 *
 * @note              The terms 'basic' and 'extended' were created to divide
 *                    the available options into reasonable parts. That's just it.
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
class nsr_plugin_data {

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
	 *  The names of the sections.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $sections
	 */
	private $sections = array( 'frontend', 'backend', 'plugin' );

	/**
	 *  The names of the notice levels used for error, warning and correction messages.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    array $nootice_levels
	 */
	private $notice_levels = array( 'notice-correction', 'notice-warning', 'notice-error' );

	/**
	 * Sets the names of the notice levels for the error messages to be generated.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function set_notice_levels() {

		$this->notice_levels = array( 'notice-correction', 'notice-warning', 'notice-error' );
	}

	/**
	 * Assigns the required parameters to its instance.
	 *
	 * @param $plugin_name
	 * @param $plugin_domain
	 */
	public function __construct( $plugin_name, $plugin_domain ) {
		
		$this->plugin_name   = $plugin_name;
		$this->plugin_domain = $plugin_domain;

		$this->set_notice_levels();
	}

	/**
	 * Returns the basic nicescroll options and their meta data.
	 *
     * @since  0.1.0
	 * @access private
	 * @return array $basic_options
	 */
	private function basic_options() {

		$basic_options = array(
			array(
				'id'               => 'enabled',
				'name'             => __( 'Nicescroll', $this->plugin_domain ),
				'callback'         => 'render_settings_field_callback',
				'settings_section' => 'basic',
				'description'      => __( 'Enable or disable Nicescroll.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'cursorcolor',
				'name'             => __( 'Cursor Color', $this->plugin_domain ),
				'callback'         => 'render_settings_field_callback',
				'settings_section' => 'basic',
				'description'      => __( 'Choose cursor color.', $this->plugin_domain ),
				'frontend_value'   => '#c1c1c1',
				'backend_value'    => '#c1c1c1',
				'input_field_type' => 'color',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'cursoropacitymin',
				'name'             => __( 'Cursor Opacity Minimum', $this->plugin_domain ),
				'callback'         => 'render_settings_field_callback',
				'settings_section' => 'basic',
				'description'      => __( 'Set opacity for when the cursor is inactive.', $this->plugin_domain ),
				'frontend_value'   => '0.0',
				'backend_value'    => '0.0',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-warning',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'cursoropacitymax',
				'name'             => __( 'Cursor Opacity Maximum', $this->plugin_domain ),
				'callback'         => 'render_settings_field_callback',
				'settings_section' => 'basic',
				'description'      => __( 'Set opacity for when the cursor is active.', $this->plugin_domain ),
				'frontend_value'   => '1.0',
				'backend_value'    => '1.0',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'cursorwidth',
				'name'             => __( 'Cursor Width', $this->plugin_domain ),
				'callback'         => 'render_settings_field_callback',
				'settings_section' => 'basic',
				'description'      => __( 'Set cursor width (in pixel).', $this->plugin_domain ),
				'frontend_value'   => '8px',
				'backend_value'    => '16px',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'cursorborderwidth',
				'name'             => __( 'Cursor Border Width', $this->plugin_domain ),
				'callback'         => 'render_settings_field_callback',
				'settings_section' => 'basic',
				'description'      => __( 'Set cursor border width (in pixel).', $this->plugin_domain ),
				'frontend_value'   => '0',
				'backend_value'    => '1px',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'cursorborderstate',
				'name'             => __( 'Cursor Border State', $this->plugin_domain ),
				'callback'         => 'render_settings_field_callback',
				'settings_section' => 'basic',
				'description'      => __( 'Select cursor border state.', $this->plugin_domain ),
				'frontend_value'   => 'solid',
				'backend_value'    => 'solid',
				'input_field_type' => 'select',
				'notice_level'     => 'none',
				'select_values'    => array( 'solid', 'dashed', 'dotted', 'double', __( 'none', $this->plugin_domain ) )
			),
			array(
				'id'               => 'cursorbordercolor',
				'name'             => __( 'Cursor Border Color', $this->plugin_domain ),
				'callback'         => 'render_settings_field_callback',
				'settings_section' => 'basic',
				'description'      => __( 'Set cursor border color.', $this->plugin_domain ),
				'frontend_value'   => '#b6b6b6',
				'backend_value'    => '#b6b6b6',
				'input_field_type' => 'color',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'cursorborderradius',
				'name'             => __( 'Cursor Border Radius', $this->plugin_domain ),
				'callback'         => 'render_settings_field_callback',
				'settings_section' => 'basic',
				'description'      => __( 'Set cursor border radius (in pixel).', $this->plugin_domain ),
				'frontend_value'   => '6px',
				'backend_value'    => '2px',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'autohidemode',
				'name'             => __( 'Autohide Mode', $this->plugin_domain ),
				'callback'         => 'render_settings_field_callback',
				'settings_section' => 'basic',
				'description'      => __( 'Select auto-hide mode.', $this->plugin_domain ),
				'frontend_value'   => 'on',
				'backend_value'    => 'off',
				'input_field_type' => 'select',
				'notice_level'     => 'none',
				'select_values'    => array( __( 'off', $this->plugin_domain ), __( 'on', $this->plugin_domain ), __( 'cursor', $this->plugin_domain )
				)
			),
		);

		return $basic_options;
	}

	/**
	 * Returns all extended nicescroll options and their meta data.
     *
     * @since  0.1.0
	 * @access private
	 * @return array $extended_options
	 */
	private function extended_options( ) {

		$extended_options = array(
			array(
				'id'               => 'zindex',
				'name'             => __( 'Z-Index', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Set z-index.', $this->plugin_domain ),
				'frontend_value'   => '9999',
				'backend_value'    => '9999',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'scrollspeed',
				'name'             => __( 'Scroll Speed', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Set scrolling speed.', $this->plugin_domain ),
				'frontend_value'   => '72',
				'backend_value'    => '72',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'mousescrollstep',
				'name'             => __( 'Mouse Scroll Step', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Set scrolling speed for the mousewheel.', $this->plugin_domain ),
				'frontend_value'   => '24',
				'backend_value'    => '24',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'touchbehavior',
				'name'             => __( 'Touch Behaviour', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Enable cursor-drag scrolling for touch-enabled computers.', $this->plugin_domain ),
				'frontend_value'   => false,
				'backend_value'    => false,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'hwacceleration',
				'name'             => __( 'Hardware Acceleration', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Switch hardware acceleration on or off.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'usetransition',
				'name'             => __( 'Transitions', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Switch transitions on or off.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'boxzoom',
				'name'             => __( 'Box Zoom', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Enable zoom for box content.', $this->plugin_domain ),
				'frontend_value'   => false,
				'backend_value'    => false,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'dblclickzoom',
				'name'             => __( 'Double Click Zoom', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Zoom activated when double click on box ("Box Zoom" must be "on").', $this->plugin_domain ),
				'frontend_value'   => false,
				'backend_value'    => false,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'gesturezoom',
				'name'             => __( 'Gesture Zoom', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Zoom on pitch in/out on box (For touch devices, set "Box Zoom" on).', $this->plugin_domain ),
				'frontend_value'   => false,
				'backend_value'    => false,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'grabcursorenabled',
				'name'             => __( 'Grab-Cursor', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Display "grab" icon ("Touch Behavior" must be possible and "on").', $this->plugin_domain ),
				'frontend_value'   => false,
				'backend_value'    => false,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			/*array(
				'id'               => 'background',
				'name'             => __( 'Background', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Change css for rail background.', $this->plugin_domain ),
				'frontend_value'   => '',
				'backend_value'    => '',
				'input_field_type' => 'text',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),*/
			array(
				'id'               => 'iframeautoresize',
				'name'             => __( 'iFrame Autoresize', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Switch auto-resize for iFrames on or off.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'cursorminheight',
				'name'             => __( 'Cursor Minimum Height', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Set the minimum cursor height.', $this->plugin_domain ),
				'frontend_value'   => '32',
				'backend_value'    => '32',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'preservenativescrolling',
				'name'             => __( 'Preserve Native Scrolling', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Scroll native scrollable areas with mouse.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'railoffset',
				'name'             => __( 'Rail Offset', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Add offset for rail position or disable it.', $this->plugin_domain ),
				'frontend_value'   => 'off',
				'backend_value'    => 'off',
				'input_field_type' => 'select',
				'notice_level'     => 'none',
				'select_values'    => array( __( 'off', $this->plugin_domain ), __( 'top', $this->plugin_domain ), __( 'left', $this->plugin_domain ) )
			),
			array(
				'id'               => 'bouncescroll',
				'name'             => __( 'Bounce Scroll', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Mobile-like scroll bouncing at the end of content.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'spacebar',
				'name'             => __( 'Spacebar', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Page down-scrolling with the spacebar.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'railpaddingtop',
				'name'             => __( 'Rail Padding Top', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Set rail padding top.', $this->plugin_domain ),
				'frontend_value'   => '0',
				'backend_value'    => '0',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-warning',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'railpaddingright',
				'name'             => __( 'Rail Padding Right', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Set rail padding right.', $this->plugin_domain ),
				'frontend_value'   => '0',
				'backend_value'    => '0',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-warning',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'railpaddingbottom',
				'name'             => __( 'Rail Padding Bottom', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Set rail padding bottom.', $this->plugin_domain ),
				'frontend_value'   => '0',
				'backend_value'    => '0',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-warning',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'railpaddingleft',
				'name'             => __( 'Rail Padding Left', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Set rail padding left.', $this->plugin_domain ),
				'frontend_value'   => '0',
				'backend_value'    => '0',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-warning',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'disableoutline',
				'name'             => __( 'Outline', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'For chrome browser, disable outline.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'horizrailenabled',
				'name'             => __( 'Horizontal Rail', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Let Nicescroll manage horizontal scrolling.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'railalign',
				'name'             => __( 'Rail Alignment Horizontal', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Select alignment of horizontal rail.', $this->plugin_domain ),
				'frontend_value'   => 'right',
				'backend_value'    => 'right',
				'input_field_type' => 'select',
				'notice_level'     => 'none',
				'select_values'    => array( __( 'right', $this->plugin_domain ),  __( 'left', $this->plugin_domain ) )
			),
			array(
				'id'               => 'railvalign',
				'name'             => __( 'Rail Alignment Vertical', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Select alignment of vertical rail.', $this->plugin_domain ),
				'frontend_value'   => 'bottom',
				'backend_value'    => 'bottom',
				'input_field_type' => 'select',
				'notice_level'     => 'none',
				'select_values'    => array( __( 'bottom', $this->plugin_domain ), __( 'top', $this->plugin_domain ) )
			),
			array(
				'id'               => 'enabletranslate3d',
				'name'             => __( 'Translate3D', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Switch translate3d on or off.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'enablemousewheel',
				'name'             => __( 'Mouse Wheel', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Let Nicescroll manage mousewheel events.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'enablekeyboard',
				'name'             => __( 'Keyboard', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Let Nicescroll manage keyboard events.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'smoothscroll',
				'name'             => __( 'Smooth Scroll', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Scroll with ease movement.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'sensitiverail',
				'name'             => __( 'Sensitive Rail', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Scrolling trough click on rail.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'enablemouselockapi',
				'name'             => __( 'Mouse Lock API', $this->plugin_domain ),
				'settings_section' => __( 'extended', $this->plugin_domain ),
				'description'      => 'Use mouse caption lock API.',
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			/*array(
				'id'               => 'cursormaxheight',
				'name'             => __( 'Cursor Maximum Height', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Switch cursor maximum height on or off.', $this->plugin_domain ),
				'frontend_value'   => false,
				'backend_value'    => false,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),*/
			array(
				'id'               => 'cursorfixedheight',
				'name'             => __( 'Cursor Fixed Height', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Select cursor fixed height or disable it.', $this->plugin_domain ),
				'frontend_value'   => 'off',
				'backend_value'    => '160',
				'input_field_type' => 'select',
				'notice_level'     => 'none',
				'select_values'    => array( __( 'off', $this->plugin_domain ), '60', '100', '160', '220', '280' )
			),
			array(
				'id'               => 'directionlockdeadzone',
				'name'             => __( 'Direction Lock Dead Zone', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Choose dead zone (in pixel) for direction lock activation.', $this->plugin_domain ),
				'frontend_value'   => '6px',
				'backend_value'    => '6px',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-warning',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'hidecursordelay',
				'name'             => __( 'Autohide Delay', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Delay in miliseconds for fading out the scrollbar.', $this->plugin_domain ),
				'frontend_value'   => '400',
				'backend_value'    => '400',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-warning',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'nativeparentscrolling',
				'name'             => __( 'Native Parent Scrolling', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Detect bottom of content and let parent scroll, as native scroll does.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'enablescrollonselection',
				'name'             => __( 'Scroll On Selection', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Auto-scrolling of content while selecting text.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'overflowx',
				'name'             => __( 'Overflow-X', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Enable or disable overflow-x.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'overflowy',
				'name'             => __( 'Overflow-Y', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Enable or disable overflow-y.', $this->plugin_domain ),
				'frontend_value'   => true,
				'backend_value'    => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'cursordragspeed',
				'name'             => __( 'Cursor Drag Speed', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Choose the momentum on cursor drag.', $this->plugin_domain ),
				'frontend_value'   => '1.2',
				'backend_value'    => '1.2',
				'input_field_type' => 'text',
				'notice_level'     => 'notice-warning',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'rtlmode',
				'name'             => __( 'RTL-Mode', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Enable or disable rtl-mode.', $this->plugin_domain ),
				'frontend_value'   => false,
				'backend_value'    => false,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'cursordragontouch',
				'name'             => __( 'Cursor Drag On Touch', $this->plugin_domain ),
				'settings_section' => 'extended',
				'description'      => __( 'Enable or disable cursor drag on touch.', $this->plugin_domain ),
				'frontend_value'   => false,
				'backend_value'    => false,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
		);

		return $extended_options;
	}

	/**
	 * Returns all plugin options and their meta data.
	 *
     * @since  0.1.0
	 * @access private
	 * @return array $plugin_options
	 */
	private function plugin_options() {

		$plugin_options = array(
			array(
				'id'               => 'backtop_enabled',
				'name'             => __( 'backTop', $this->plugin_domain ),
				'settings_section' => 'plugin',
				'description'      => __( 'Activate or deactivate backTop.', $this->plugin_domain ),
				'value'            => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
			array(
				'id'               => 'scrollto_enabled',
				'name'             => __( 'scrollTo', $this->plugin_domain ),
				'settings_section' => 'plugin',
				'description'      => __( 'Activate or deactivate scrollTo.', $this->plugin_domain ),
				'value'            => true,
				'input_field_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),
		);

		return $plugin_options;
	}

	/* ------------------------------------------------------------------------ *
	 * Seeder
	 * ------------------------------------------------------------------------ */
	/**
	 * Writes the requested option group(s) values to the database.
	 *
	 * @since  0.1.0
	 * @param  string $section | void
	 * @return void
	 */
	public function seed_default_options( $section = null ) {

		if ( isset( $section ) ) {

			add_option( $this->plugin_name . '_' . $section . '_options', $this->get_default_options( $section ) );
		} else {

			foreach( $this->get_sections() as $section ) {

				add_option( $this->plugin_name . '_' . $section . '_options', $this->get_default_options( $section ) );
			}
		}
	}

	/* ------------------------------------------------------------------------ *
	* Resetter
	* ------------------------------------------------------------------------ */
	/**
	 * Updates the database with the requested option group(s)'s options.
	 *
	 * @since  0.1.0
	 * @param  string $section | void
	 * @return bool
	 */
	public function reset_settings( $section = null ) {

		if ( isset( $section ) ) {

			update_option( $this->plugin_name . '_' . $section . '_options', $this->get_default_options( $section ) );

			return true;
		} else {

			foreach ( $this->get_sections() as $section ) {

				update_option( $this->plugin_name . '_' . $section . '_options', $this->get_default_options( $section ) );
			}

			return true;
		}
	}

	/* ------------------------------------------------------------------------ *
	 * Getters
	 * ------------------------------------------------------------------------ */
	/**
	 * Retrieves the default options per requested section.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  string $section
	 * @return array $section_defaults
	 */
	private function get_the_default_options( $section ) {

		if ( 'plugin' == $section ) {

			$section_defaults = $this->get_plugin_options( $section );
		} else {

			$section_defaults = array_merge( $this->get_basic_options( $section ), $this->get_extended_options( $section ) );
		}

		return $section_defaults;
	}

	/**
	 * Accesses the method to retrieve the default options per requested section.
	 *
	 * @param  string $section
	 * @return array $section_defaults
	 */
	public function get_default_options( $section ) {

		return $this->get_the_default_options( $section );
	}

	/**
	 * Calls the method to retrieve an array with the options meta data.
	 *
	 * @param  string $settings_section
	 * @access private
	 * @return array  $options_meta
	 */
	public function get_options_meta( $settings_section ) {

		return $this->filter_options_meta( $settings_section );
	}

	/**
	 * Extracts the necessary meta data from the requested options array.
	 *
	 * @param  string $settings_section
	 * @access private
	 * @return array  $options_meta
	 */
	private function filter_options_meta( $settings_section ) {

		$options = $this->retrieve_default_options( $settings_section );
		$options_meta = [ ];

		foreach ( $options as $index => $option ) {

			$options_meta[ $option['id'] ] = array(
				'id'               => $option['id'],
				'name'             => $option['name'],
				'settings_section' => $option['settings_section'],
				'description'      => $option['description'],
				'input_field_type' => $option['input_field_type'],
				'select_values'    => $option['select_values']
			);
		}

		return $options_meta;
	}

	/**
	 * Retrieves the options array for the requested settings section.
	 *
	 * @param  string $settings_section
	 * @access private
	 * @return array  $options
	 */
	private function retrieve_default_options( $settings_section ) {

		$options = null;

		if( 'plugin' == $settings_section ) {

			$options = $this->plugin_options();
		} else if ( 'extended' == $settings_section ) {

			$options = $this->extended_options();
		} else {

			$options = $this->basic_options();
		}

		return $options;
	}

	/**
	 * Returns the meta data necessary for rendering the requested settings section heading.
	 *
	 * @since  0.1.0
	 * @param  string $section
	 * @return array  $heading
	 */
	public function get_section_heading( $section ) {

		$basic_settings = array(
			'settings_section' => 'basic',
			'title'            => __( 'Basic Settings', $this->plugin_domain ),
			'callback'         => 'basic_settings_section_callback',
		);

		$extended_settings = array(
			'settings_section' => 'extended',
			'title'            => __( 'Extended Settings', $this->plugin_domain ),
			'callback'         => 'extended_settings_section_callback',
		);

		$plugin_settings = array(
			'settings_section' => 'plugin',
			'title'            => __( 'Plugin Settings', $this->plugin_domain ),
			'callback'         => 'plugin_settings_section_callback'
		);

		switch ( $section ) {

			case( 'basic' == $section );
				$heading = $basic_settings;
				break;

			case( 'extended' == $section );
				$heading = $extended_settings;
				break;

			case( 'plugin' == $section );
				$heading = $plugin_settings;
				break;

			default:
				return false;
		}

		return $heading;
	}

	/**
	 * Processes the basic options array and returns the (now prefixed) id/value pair.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  string $view
	 * @return array  $basic_options
	 */
	private function get_basic_options( $view ) {

		$basic_options = [ ];

		$options = $this->basic_options();

		foreach ( $options as $index => $option ) {

			$basic_options[ $view . '_' . $option['id'] ] = $option[ $view . '_' . 'value' ];
		}

		return $basic_options;
	}

	/**
	 * Processes the extended options array and returns the (now prefixed) id/value pair.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  string $view
	 * @return array  $extended_options
	 */
	private function get_extended_options( $view ) {

		$extended_options = [ ];

		$options = $this->extended_options();

		foreach ( $options as $index => $option ) {

			$extended_options[ $view . '_' . $option['id'] ] = $option[ $view . '_' . 'value' ];
		}

		return $extended_options;
	}

	/**
	 * Processes the plugin options array and returns the (now prefixed) id/value pair.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  string $section
	 * @return array  $plugin_options
	 */
	private function get_plugin_options( $section ) {

		$plugin_options = [ ];

		$options = $this->plugin_options();

		foreach ( $options as $index => $option ) {

			$plugin_options[ $section . '_' . $option['id'] ] = $option['value'];
		}

		return $plugin_options;
	}

	/**
	 * Filters the array containing all default options using the actual errors to
	 * only return error meta data related to these validation errors.

	 *
*@since  0.1.0
	 * @uses   sort_errors_meta_data_by_notice_level()
	 * @param  array $errors
	 * @return array $prefixed_error_meta
	 */
	public function get_errors_meta_data( $errors ) {

		$options             = array_merge( $this->basic_options(), $this->extended_options(), $this->plugin_options() );
		$unprefixed_errors   = [ ];
		$prefixed_error_meta = [ ];

		// Retrieves the section name.
		$section = $errors[0];

		// Shifts the section name.
		array_shift( $errors );

		// Removes the prefixed section name, so we can match the id against the corresponding one in the options array to filter the meta data.
		foreach ( $errors as $index => $id ) {

			$id = str_replace( $section . '_', '', $id );
			$unprefixed_errors[ $index ] = $id;
		}

		// Filters the actual errors and their meta data, and prefixes the id again with the section.
		foreach ( $options as $index => $meta ) {

			if ( false !== array_search( $meta['id'], $unprefixed_errors ) ) {

				$prefixed_error_meta[ $section . '_' . $meta['id'] ] = array( 'name' => $meta['name'], 'notice_level' => $meta['notice_level'] );
			}
		}

		// Calls a helper function to sort the errors by notice level and returns the array.
		return $this->sort_errors_meta_data_by_notice_level( $prefixed_error_meta );
	}

	/**
	 * Orders the errors array according to the entries in $notice_levels and returns it.
	 *
     * @since  0.1.0
	 * @access private
	 * @param  array $prefixed_error_meta
	 * @return array $errors
	 */
	private function sort_errors_meta_data_by_notice_level( $prefixed_error_meta ) {

		$errors = [];

		// For each notice level...
		foreach ( $this->notice_levels as $index => $notice_level ) {

			// ...it loops trough the array containing the errors and adds the matches to the following array.
			foreach ( $prefixed_error_meta as $id => $meta_data ) {

				// That way the error meta gets ordered by notice level.
				if ( isset( $meta_data['notice_level'] ) && $notice_level === $meta_data['notice_level'] ) {

					$errors[ $id ] = $meta_data;
				}
			}
		}

		return apply_filters( 'sort_errors_meta_data_by_notice_level', $errors, $prefixed_error_meta );
	}

	/**
	 * Returns the basic options count (the amount of basic option settings fields), which gets localized.
	 *
	 * @since  0.1.0
	 * @see    admin/menu/js/menu.js
	 * @see    admin/menu/includes/menu-localisation.php
	 * @return array $basic_options_count
	 */
	public function get_basic_options_count() {

		$count = count( $this->basic_options() );

		$basic_options_count = [ 'basic_options_count' => $count ];

		return $basic_options_count;
	}

	/**
	 * Retrieves the options.
	 * If no section is given it retrieves all settings from all settings groups,
	 * otherwise per requested section.
	 *
	 * @since  0.1.0
	 * @param  array $section | void
	 * @return array $options
	 */
	public function get_options( $section = null ) {

		$options = [];

		if( isset( $section ) ) {

			// Options per requested section.
			return $this->get_the_options( $section );

		} else  {

			// All options.
			foreach( $this->get_sections() as $section ) {

				$options = array_push( $options, $this->get_the_options( $section ) );
			}

			return $options;
		}

	}

	/**
	 * Gets the actual options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  string $section
	 * @return array
	 */
	private function get_the_options( $section ) {

		return get_option( $this->plugin_name . '_' . $section . '_options' );
	}

	/**
	 * Retrieves the names of the sections.
	 *
	 * @since  0.1.0
	 * @return array $sections
	 */
	public function get_sections() {

		return $this->sections;
	}

}
