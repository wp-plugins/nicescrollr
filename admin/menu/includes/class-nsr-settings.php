<?php

/**
 * The class that deals with the settings api.
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
class nsr_settings {

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
	 *  The name of the section.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $section
	 */
	private $section;

	/**
	 * The name of this particular option group.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $option_group
	 */
	private $option_group;

	/**
	 * The name of the backend settings group.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string  $settings_group
	 */
	private $settings_group;

	/**
	 * The array containing the default options.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    array   $default_options
	 */
	private $default_options;

	/**
	 * The reference to the options class.
	 *
     * @since  0.1.0
	 * @access private
	 * @var    object $Plugin_Data
	 */
	private $Plugin_Data;

	/**
	 * The reference to the validation class.
	 *
     * @since  0.1.0
	 * @access private
	 * @var    object $Validation
	 */
	private $Validation;

	/**
	 * Sets the name of the option group.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function set_option_group() {

		$this->option_group = $this->plugin_name . '_' . $this->section . '_options';
	}

	/**
	 * Sets the name of the settings group.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function set_settings_group() {

		$this->settings_group = $this->section . '_settings_group';
	}

	/**
	 * Retrieves the default options for the requested section and sets them.
	 *
	 * @since  0.1.0
	 * @uses   get_default_options()
	 * @see    admin/menu/includes/class-nsr-plugin-data.php
	 * @access private
	 * @return void
	 */
	private function set_default_options() {

		$this->default_options = $this->Plugin_Data->get_default_options( $this->section );
	}

	/**
	 * Kicks off the settings class.
	 *
	 * @since 0.1.0
	 * @param string $plugin_name
	 * @param string $plugin_domain
	 * @param string $section
	 */
	public function __construct( $plugin_name, $plugin_domain,  $section ) {

		$this->plugin_name          = $plugin_name;
		$this->plugin_domain        = $plugin_domain;
		$this->section              = $section;

		$this->load_dependencies();
		$this->set_option_group();
		$this->set_settings_group();

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

		// The class that holds all plugin-related data.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "includes/class-nsr-plugin-data.php";

		// The class responsible for the validation tasks.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "includes/class-nsr-validation.php";

		$this->Plugin_Data = new nsr_plugin_data( $this->get_plugin_name(), $this->get_plugin_domain() );
		$this->Validation  = new nsr_validation( $this->get_plugin_name(), $this->get_plugin_domain(), $this->get_plugin_data(), $this->get_section() );
	}

	/**
	 * Adds the actions to be executed with WordPress:
	 * - Registers the settings group and the validation-callback with WordPress
	 * - Checks if there are any options in the database and seeds them if there are none stored
	 * - Loads the default backend settings to populate the placeholders on empty input fields
	 * - Registers the settings sections and fields with WordPress
	 *
	 * @since  0.1.0
	 * @access private
	 * @return void
	 */
	private function init() {

		add_action( 'admin_init', array( &$this, 'register_settings' ), 1 );
		add_action( 'admin_init', array( &$this, 'check_for_options' ), 2 );
		add_action( 'admin_init', array( &$this, 'load_default_options' ), 3 );
		add_action( 'admin_init', array( &$this, 'initialize_options' ), 10 );
	}

	/**
	 * Registers the settings group and the validation-callback with WordPress.
	 *
	 * @hooked_action
	 *
     * @since  0.1.0
	 * @return void
	 */
	public function register_settings() {

		register_setting( $this->option_group, $this->option_group, array( &$this->Validation, 'run' ) );
	}

	/**
	 * Checks for options in the database and seeds the default values if the options group should be empty.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @uses   seed_default_options()
	 * @see    admin/menu/includes/class-nsr-plugin-data.php
	 * @return void
	 */
	public function check_for_options() {

		if ( false === get_option( $this->option_group ) ) {

			$this->Plugin_Data->seed_default_options( $this->section );
		}
	}

	/**
	 * Calls the function that retrieves the default options.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function load_default_options() {

		$this->set_default_options();
	}

	/* ------------------------------------------------------------------------ *
	 * Settings Initialisation
	 * ------------------------------------------------------------------------ */
	/**
	 * Registers the sections, their headings and settings fields with WordPress.
	 *
	 * @hooked_action
	 *
	 * @since  0.1.0
	 * @uses   get_section_heading()
	 * @uses   get_options_meta()
	 * @see    admin/menu/includes/class-nsr-plugin-data.php
	 * @return void
	 */
	public function initialize_options() {

		if( 'plugin' == $this->section ) {

			$this->add_settings_section( $this->Plugin_Data->get_section_heading( 'plugin' ) );

			$this->add_settings_field( $this->Plugin_Data->get_options_meta( 'plugin' ) );
		} else {

			$this->add_settings_section( $this->Plugin_Data->get_section_heading( 'basic' ) );

			$this->add_settings_field( $this->Plugin_Data->get_options_meta( 'basic' ) );

			$this->add_settings_section( $this->Plugin_Data->get_section_heading( 'extended' ) );

			$this->add_settings_field( $this->Plugin_Data->get_options_meta( 'extended' ) );
		}
	}

	/**
	 * Registers the settings sections with WordPress.
	 *
     * @since  0.1.0
	 * @param  array $section_heading
	 * @return void
	 */
	private function add_settings_section( $section_heading ) {

		add_settings_section(
			$section_heading['settings_section'] . '_settings_section',
			$section_heading['title'],
			array( &$this, $section_heading['callback'] ),
			$this->settings_group
		);
	}

	/**
	 * Registers the settings fields with WordPress.
	 *
	 * @since  0.1.0
	 * @param  array $settings_fields
	 * @return void
	 */
	private function add_settings_field( $settings_fields ) {

		foreach ( $settings_fields as $id => $settings_field ) {

			add_settings_field(
				$this->section . '_' . $settings_field['id'],
				$settings_field['name'],
				array( &$this, 'render_settings_field_callback' ),
				$this->settings_group,
				$settings_field['settings_section'] . '_settings_section',
				array(
					'id'               => $this->section . '_' . $settings_field['id'],
					'description'      => $settings_field['description'],
					'input_field_type' => $settings_field['input_field_type'],
					'select_values'    => $settings_field['select_values']
				)
			);
		}
	}

	/* ------------------------------------------------------------------------ *
	 * Settings Section Callback
	 * ------------------------------------------------------------------------ */
	/**
	 * Renders the description for the "basic settings section".
	 *
	 * @since 0.1.0
	 * @return echo
	 */
	public function basic_settings_section_callback() {

		/*echo '<p>' . __( 'Customize the basic Nicescroll settings.', $this->plugin_domain ) . '</p>';*/
	}

	/**
	 * Renders the description for the "extended settings section".
	 *
	 * @since 0.1.0
	 * @return echo
	 */
	public function extended_settings_section_callback() {

		/*echo '<p>' . __( 'Customize the extended Nicescroll settings.', $this->plugin_domain ) . '</p>';*/
	}

	/**
	 * Renders the description for the "plugin section".
	 *
	 * @since 0.1.0
	 * @return echo
	 */
	public function plugin_settings_section_callback() {
		/*echo '<p>' . __( 'Set the options of the plugin.', $this->plugin_domain ) . '</p>';*/
	}

	/* ------------------------------------------------------------------------ *
	 * Settings Field Display Callback
	 * ------------------------------------------------------------------------ */
	/**
	 * Calls the corresponding callback function that renders the section field.
	 *
	 * @since  0.1.0
	 * @param  array $args
	 * @return void
	 */
	public function render_settings_field_callback( $args ) {

		switch ( $args['input_field_type'] ) {

			case( $args['input_field_type'] == 'checkbox' );

				$this->echo_checkbox_field( $args );
				break;

			case( $args['input_field_type'] == 'text' );

				$this->echo_text_field( $args );
				break;

			case( $args['input_field_type'] == 'color' );

				$this->echo_color_picker_field( $args );
				break;

			case( $args['input_field_type'] == 'select' );

				$this->echo_select_field( $args );
				break;
		}
	}

	/* ------------------------------------------------------------------------ *
	 * Settings Field Display Callback Callbacks
	 * ------------------------------------------------------------------------ */
	/**
	 * Renders a settings field with a checkbox.
	 *
	 * @since 0.1.0
	 * @param $args
	 * @return echo
	 */
	public function echo_checkbox_field( $args ) {

		$options = get_option( $this->option_group );

		$html = '<p class="nsr-input-container">';
		$html .= '<input type="checkbox" id="' . $args['id'] . '" class="ios-switch" name=' . $this->option_group . '[' . $args['id'] . ']" value="1" ' . checked( 1, isset( $options[ $args['id'] ] ) ? $options[ $args['id'] ] : 0, false ) . ' />';
		$html .= '</p>';

		$html .= '<p class="nsr-label-container">';
		$html .= '<label class="label-for-ios-switch" for="' . $args['id'] . '">&nbsp;' . $args['description'] . '</label>';
		$html .= '</p>';

		echo $html;
	}

	/**
	 * Renders a settings field with a text field.
	 *
	 * @since 0.1.0
	 * @param $args
	 * @return echo
	 */
	public function echo_text_field( $args ) {

		$options = get_option( $this->option_group );

		$html = '<p class="nsr-input-container">';
		$html .= '<input type="text" id="' . $args['id'] . '" name="' . $this->option_group . '[' . $args['id'] . ']" Placeholder="' . $this->default_options[ $args['id'] ] . '" value="' . $options[ $args['id'] ] . '" />';
		$html .= '</p>';

		$html .= '<p class="nsr-label-container">';
		$html .= '<label class="label-for-ios-switch" for="' . $args['id'] . '">&nbsp;' . $args['description'] . '</label>';
		$html .= '</p>';

		echo $html;
	}

	/**
	 * Renders a settings field with a color picker.
	 *
	 * @since 0.1.0
	 * @param $args
	 * @return echo
	 */
	public function echo_color_picker_field( $args ) {

		$options = get_option( $this->option_group );

		$html = '<p class="nsr-input-container">';
		$html .= '<input type="text"  id="' . $args['id'] . '" name="' . $this->option_group . '[' . $args['id'] . ']" Placeholder="' . $this->default_options[ $args['id'] ] . '" value="' . $options[ $args['id'] ] . '" class="' . $args['id'] . '" />';
		$html .= '</p>';

		$html .= '<p class="nsr-label-container">';
		$html .= '<label class="label-for-ios-switch" for="' . $args['id'] . '">&nbsp;' . $args['description'] . '</label>';
		$html .= '</p>';

		echo $html;
	}

	/**
	 * Renders a settings field with a select dropdown.





*
*@since  0.1.0
	 * @uses   translate_to_custom_locale()
	 * @param  $args
	 * @return echo
	 */
	public function echo_select_field( $args ) {

		if( get_locale() !== 'en_US' ) {

			$options = $this->translate_to_custom_locale();
		} else {

			$options = get_option( $this->option_group );
		}

		$html = '<p class="nsr-input-container">';
		$html .= '<select name="' . $this->option_group . '[' . $args['id'] . ']" class="floating-element nsr-fancy-select" id="' . $args['id'] . '">';

		foreach ( $args['select_values'] as $value ) {

			$html .= '<option value="' . $value . '"' . selected( $options[ $args['id'] ], $value, false ) . '>' . $value . '</option>';
		}

		$html .= '</select>';
		$html .= '</p>';

		$html .= '<p class="nsr-label-container">';
		$html .= '<label class="label-for-ios-switch" for="' . $args['id'] . '">&nbsp;' . $args['description'] . '</label>';
		$html .= '</p>';

		echo $html;
	}

	/**
	 * Translation helper function for some select box values.
	 * Since Nicescroll makes use of strings as parameters - and it does only "speak" English -
	 * this function translates the values that were stored in the default locale into strings of the current locale.
	 * This way, the localisation feature remains fully functional.
	 *
	 * @since  0.1.0
	 * @access private
	 * @see    admin/menu/includes/class-nsr-validation.php | translate_to_default_locale()
	 * @return mixed|void
	 */
	public function translate_to_custom_locale() {

		$options = get_option( $this->option_group );

		$output = [ ];

		foreach ( $options as $option_name => $value ) {

			switch ( $option_name ) {

				case( $option_name == $this->section . '_' . 'cursorborderstate' );
					if ( isset( $value ) && $value == 'none' ) {

						$output[ $option_name ] = __( 'none', $this->plugin_domain );
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				case( $option_name == $this->section . '_' . 'autohidemode' );

					if ( isset( $value ) && $value == 'off' ) {

						$output[ $option_name ] = __( 'off', $this->plugin_domain );
					} else if ( isset( $value ) && $value == 'on' ) {

						$output[ $option_name ] = __( 'on', $this->plugin_domain );
					} else if ( isset( $value ) && $value == 'cursor' ) {

						$output[ $option_name ] = __( 'cursor', $this->plugin_domain );
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				case( $option_name == $this->section . '_' . 'railoffset' );

					if ( isset( $value ) && $value == 'off' ) {

						$output[ $option_name ] = __( 'off', $this->plugin_domain );
					} else if ( isset( $value ) && $value == 'top' ) {

						$output[ $option_name ] = __( 'top', $this->plugin_domain );
					} else if ( isset( $value ) && $value == 'left' ) {

						$output[ $option_name ] = __( 'left', $this->plugin_domain );
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				case( $option_name == $this->section . '_' . 'railalign' );

					if ( isset( $value ) && $value == 'right' ) {

						$output[ $option_name ] = __( 'right', $this->plugin_domain );
					} else if ( isset( $value ) && $value == 'left' ) {

						$output[ $option_name ] = __( 'left', $this->plugin_domain );
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				case( $option_name == $this->section . '_' . 'railvalign' );

					if ( isset( $value ) && $value == 'bottom' ) {

						$output[ $option_name ] = __( 'bottom', $this->plugin_domain );
					} else if ( isset( $value ) && $value == 'top' ) {

						$output[ $option_name ] = __( 'top', $this->plugin_domain );
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				case( $option_name == $this->section . '_' . 'cursorfixedheight' );

					if ( isset( $value ) && $value == 'off' ) {

						$output[ $option_name ] = __( 'off', $this->plugin_domain );
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				default:
					$output[ $option_name ] = $value;
			}
		}

		return $output;
	}

	/* ------------------------------------------------------------------------ *
	 * Getters
	 * ------------------------------------------------------------------------ */
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

		return $this->plugin_domain;
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

	/**
	 * Retrieves the reference to the "plugin data" class.
	 *
	 * @since  0.1.0
	 * @return object $Plugin_Data
	 */
	public function get_plugin_data() {

		return $this->Plugin_Data;
	}

}
