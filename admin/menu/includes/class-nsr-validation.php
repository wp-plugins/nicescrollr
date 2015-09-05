<?php

/**
 * The class responsible for sanitizing and validating the user inputs.
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
class nsr_validation {

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
	 * The reference to the options class.
     *
     * @since  0.1.0
	 * @access private
	 * @var    object $Plugin_Data
	 */
	private $Plugin_Data;

	/**
	 * The name of the section to be validated.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    string $section
	 */
	private $section;

	/**
	 * Assigns the required parameters to its instance.
	 *
     * @since 0.1.0
	 * @param string $plugin_name
	 * @param string $plugin_domain
	 * @param object $Plugin_Data
	 * @param string $section
	 */
	public function __construct( $plugin_name, $plugin_domain, $Plugin_Data, $section ) {

		$this->plugin_name   = $plugin_name;
		$this->plugin_domain = $plugin_domain;
		$this->Plugin_Data   = $Plugin_Data;
		$this->section       = $section;
	}

	/**
	 * Kicks off sanitisation and validation - if there's any input given.
	 *
	 * @since  0.1.0
	 * @param  array $input
	 * @return array $output
	 */
	public function run( $input ) {

		if ( isset( $input ) ) {

			$input = $this->sanitize( $input );

			return $this->validate( $input );
		} else {

			return true;
		}
	}

	/**
	 * Sanitizes the input.
	 *
	 * @since  0.1.0
	 * @param  array $input
	 * @return array $output
	 */
	private function sanitize( $input ) {

		$output = array();

		foreach ( $input as $key => $value ) {

			if ( isset ( $input[ $key ] ) ) {
				$output[ $key ] = strip_tags( stripslashes( $value ) );
			}
		}

		return apply_filters( 'sanitize', $output, $input );
	}

	/**
	 * Validates the input.

     *
*@since  0.1.0
	 * @uses   get_default_options()
	 * @see    admin/menu/includes/class-nsr-plugin-data.php
	 * @uses   translate_to_default_locale()
	 * @param  array $input
	 * @return array $output
	 */
	private function validate( $input ) {

		$defaults = $this->Plugin_Data->get_default_options( $this->section );
		$output   = [];
		$validation_errors   = [];

		foreach ( $input as $option_name => $value ) {

			if ( isset( $option_name ) ) {

				switch ( $option_name ) {

					case ( $option_name == $this->section . '_' . 'cursorcolor' );

						if ( ! preg_match( '/^#[a-f0-9]{3,6}$/i', $value ) ) {

							$value = $defaults[ $option_name ];
							set_transient( $option_name, $this->cursorcolor_error_message(), 60 );
							array_push( $validation_errors, $option_name );
						}

						break;

					case ( $option_name == $this->section . '_' . 'cursoropacitymin' );

						if ( $value !== '' ) {

							if ( ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) || ( ! ( (int) $value >= 0 ) && ! ( (int) $value <= 1.00 ) ) ) {

								$value = '';
								set_transient( $option_name, $this->cursoropacitymin_error_message(), 60 );
								array_push( $validation_errors, $option_name );
							}
						}

						break;

					case ( $option_name == $this->section . '_' . 'cursoropacitymax' );

						if ( ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) || ( ! ( (int)$value >= 0 ) && ! ( (int)$value <= 1.00 ) ) ) {

							$value = $defaults[ $option_name ];
							set_transient( $option_name, $this->cursoropacitymax_error_message(), 60 );
							array_push( $validation_errors, $option_name );
						}

						break;

					case ( $option_name == $this->section . '_' . 'cursorwidth' );

						// Pattern to remove any reasonable unit for pixels.
						$pattern = '/pixels+|pixel+|px+/i';
						if ( preg_match( $pattern, $value ) ) {

							$value = preg_replace( $pattern, '', $value );

							$value = trim( $value );
						}
						// If the value is not an integer after removing any reasonable unit for pixels...
						if ( ! ctype_digit( $value ) ) {

							$value = $defaults[ $option_name ];
							set_transient( $option_name, $this->cursorwidth_error_message(), 60 );
							array_push( $validation_errors, $option_name );
						} else if ( ctype_digit( $value ) && $value != 0 ) {
							// The value is an integer, and it gets the unit added again.
							$value = $value . 'px';
						}

						break;

					case ( $option_name == $this->section . '_' . 'cursorborderwidth' );

						// Pattern to remove any reasonable unit for pixels.
						$pattern = '/pixels+|pixel+|px+/i';
						if ( preg_match( $pattern, $value ) ) {

							$value = preg_replace( $pattern, '', $value );

							$value = trim( $value );
						}
						// If the value is not an integer after removing any reasonable unit for pixels...
						if ( ! ctype_digit( $value ) ) {

							$value = $defaults[ $option_name ];
							set_transient( $option_name, $this->cursorborderwidth_error_message(), 60 );
							array_push( $validation_errors, $option_name );
						} else if ( ctype_digit( $value ) && $value != 0 ) {
							// The value is an integer, and it gets the unit added again.
							$value = $value . 'px';
						}

						break;

					case ( $option_name == $this->section . '_' . 'cursorbordercolor' );

						if ( isset( $value ) && ! preg_match( '/^#[a-f0-9]{3,6}$/i', $value ) ) {

							$value = $defaults[ $option_name ];
							set_transient( $option_name, $this->cursorbordercolor_error_message(), 60 );
							array_push( $validation_errors, $option_name );
						}

						break;

					case ( $option_name == $this->section . '_' . 'cursorborderradius' );

						// Pattern to remove any reasonable unit for pixels.
						$pattern = '/pixels+|pixel+|px+/i';
						if ( preg_match( $pattern, $value ) ) {

							$value = preg_replace( $pattern, '', $value );

							$value = trim( $value );
						}
						// If the value is not an integer after removing any reasonable unit for pixels...
						if ( ! ctype_digit( $value ) ) {

							$value = $defaults[ $option_name ];
							set_transient( $option_name, $this->cursorborderradius_error_message(), 60 );
							array_push( $validation_errors, $option_name );
						} else if ( ctype_digit( $value ) && $value != 0 ) {
							// The value is an integer, and it gets the unit added again.
							$value = $value . 'px';
						}

						break;

					case( $option_name == $this->section . '_' . 'zindex' );

						if ( ! ctype_digit( $value ) ) {

							if( ! ctype_digit( ltrim( $value, '-' ) ) ) {

								$value = $defaults[ $option_name ];
								set_transient( $option_name, $this->zindex_error_message(), 60 );
								array_push( $validation_errors, $option_name );
							}
						}

						break;

					case ( $option_name == $this->section . '_' . 'scrollspeed' );

						if ( ! ctype_digit( $value ) || $value === '0' ) {

							$value = $defaults[ $option_name ];
							set_transient( $option_name, $this->scrollspeed_error_message(), 60 );
							array_push( $validation_errors, $option_name );
						}

						break;

					case( $option_name == $this->section . '_' . 'mousescrollstep' );

						if ( ! ctype_digit( $value ) || $value === '0' ) {

							$value = $defaults[ $option_name ];
							set_transient( $option_name, $this->mousescrollstep_error_message(), 60 );
							array_push( $validation_errors, $option_name );
						}

						break;

					case( $option_name == $this->section . '_' . 'cursorminheight' );

						if ( $value !== '' ) {

							// Pattern to remove any reasonable unit for pixels.
							$pattern = '/pixels+|pixel+|px+/i';
							if ( preg_match( $pattern, $value ) ) {

								$value = preg_replace( $pattern, '', $value );

								$value = trim( $value );
							}
							// If the value is not an integer after removing any reasonable unit for pixels...
							if ( ! ctype_digit( $value ) ) {

								$value = $defaults[ $option_name ];
								set_transient( $option_name, $this->cursorminheight_error_message(), 60 );
								array_push( $validation_errors, $option_name );
							}
						}

						break;

					case( ( $option_name == $this->section . '_' . 'railpaddingtop' ) || ( $option_name == $this->section . '_' . 'railpaddingright' ) ||
					      ( $option_name == $this->section . '_' . 'railpaddingbottom' ) || ( $option_name == $this->section . '_' . 'railpaddingleft' ));

						if ( $value !== '' ) {

							if ( ! ctype_digit( $value ) ) {

								$value = '';
								set_transient( $option_name, $this->railpadding_error_message(), 60 );
								array_push( $validation_errors, $option_name );
							}
						}

						break;

					case( $option_name == $this->section . '_' . 'hidecursordelay' );

						if ( $value !== '' ) {

							if ( ! ctype_digit( $value ) ) {

								$value = '';
								set_transient( $option_name, $this->hidecursordelay_error_message(), 60 );
								array_push( $validation_errors, $option_name );
							}
						}

						break;

					case( $option_name == $this->section . '_' . 'directionlockdeadzone' );

						if ( $value !== '' ) {

							// Pattern to remove any reasonable unit for pixels.
							$pattern = '/pixels+|pixel+|px+/i';
							if ( preg_match( $pattern, $value ) ) {

								$value = preg_replace( $pattern, '', $value );

								$value = trim( $value );
							}
							// If the value is not an integer after removing any reasonable unit for pixels...
							if ( ! ctype_digit( $value ) ) {

								$value = '';
								set_transient( $option_name, $this->directionlockdeadzone_error_message(), 60 );
								array_push( $validation_errors, $option_name );
							} else if ( ctype_digit( $value ) && $value != 0 ) {
								// The value is an integer, and it gets the unit added again.
								$value = $value . 'px';
							}
						}

						break;

					case( $option_name == $this->section . '_' . 'cursordragspeed' );

						if ( $value !== '' ) {

							if ( ! preg_match( '/^[0-9]+(\.[0-9]{1,2})?$/', $value ) ) {

								$value = '';
								set_transient( $option_name, $this->cursordragspeed_error_message(), 60 );
								array_push( $validation_errors, $option_name );
							}
						}

						break;
				}

				// The array holding the processed values.
				$output[ $option_name ] = $value;
			}
		}

		if( get_locale() !== 'en_US' ) {

			$output = $this->translate_to_default_locale( $output );
		}

		// If there were errors and transients were created, we create one more containing the ids of the previously created ones.
		if ( isset( $validation_errors ) && ( ! empty( $validation_errors ) ) ) {

			// Adds the section name as first entry to the array.
			array_unshift( $validation_errors, $this->section );

			set_transient( 'nicescrollr_validation_errors', $validation_errors, 60 );
		}

		return apply_filters( 'validate', $output, $input );
	}

	/**
	 * Helper function, that translates "non-default-locale strings" into strings of the default locale.
	 * This task is necessary, since Nicescroll needs some strings as parameters and they have to be served in English.
	 * With this step, localisation remains fully functional.
	 *
	 * @since  0.1.0
	 * @access private
	 * @see    admin/menu/includes/class-nsr-settings.php | translate_to_custom_locale()
	 * @param  $input
	 * @return mixed|void
	 */
	public function translate_to_default_locale( $input ) {

		$output = [];

		foreach( $input as $option_name => $value ) {

			switch ( $option_name ) {

				case( $option_name == $this->section . '_' . 'cursorborderstate' );
					if ( isset( $value ) && $value == __( 'none', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'none';
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				case( $option_name == $this->section . '_' . 'autohidemode' );

					if ( isset( $value ) && $value == __( 'off', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'off';
					} else if ( isset( $value ) && $value == __( 'on', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'on';
					} else if ( isset( $value ) && $value == __( 'cursor', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'cursor';
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				case( $option_name == $this->section . '_' . 'railoffset' );

					if ( isset( $value ) && $value == __( 'off', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'off';
					} else if ( isset( $value ) && $value == __( 'top', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'top';
					} else if ( isset( $value ) && $value == __( 'left', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'left';
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				case( $option_name == $this->section . '_' . 'railalign' );

					if ( isset( $value ) && $value == __( 'right', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'right';
					} else if ( isset( $value ) && $value == __( 'left', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'left';
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				case( $option_name == $this->section . '_' . 'railvalign' );

					if ( isset( $value ) && $value == __( 'bottom', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'bottom';
					} else if ( isset( $value ) && $value == __( 'top', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'top';
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				case( $option_name == $this->section . '_' . 'cursorfixedheight' );

					if ( isset( $value ) && $value == __( 'off', $this->plugin_domain ) ) {

						$output[ $option_name ] = 'off';
					} else {
						$output[ $option_name ] = $value;
					}
					break;

				default:
					$output[ $option_name ] = $value;
			}
		}

		return apply_filters( 'translate_to_default_locale', $output, $input );
	}

	/* ------------------------------------------------------------------------ *
	 * Error Messages
	 * ------------------------------------------------------------------------ */
	/**
	 * Returns the specified error message.
	 *
	 * @since   0.1.0
	 * @return \WP_Error
	 */
	public function cursorcolor_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a hexadecimal color value. It was reset to its default. To customize it, please input a color value like '#fff' or '#0073AA'.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursoropacitymin_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive number between 0 and 1, with max two decimal places (or left blank).", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursoropacitymax_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive number between 0 and 1, with max two decimal places. It was reset to it's default.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorwidth_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value including 0 (zero). It was reset to it's default.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorborderwidth_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value including 0 (zero). It was reset to it's default.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorbordercolor_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a hexadecimal color value. (It was reset to its default.) To customize it, please input a color value like '#fff' or '#0073AA'.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorborderradius_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive, integer pixel value or left blank. It was reset to it's default.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function zindex_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be an integer value. It was reset to it's default.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function scrollspeed_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer but must not be 0 (zero). To aviod unwanted scrolling behaviour, the scrollspeed was reset to its default. (Note: If you intended to disable the mousewheel, please visit the extended settings panel.)", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function mousescrollstep_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer but it must not be 0 (zero). To aviod unwanted scrolling behaviour, it was reset to its default.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursorminheight_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero). It was reset to it's default.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function railpadding_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero) or left blank.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function directionlockdeadzone_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero) or left blank. To customize it, please have a look at its placeholder.", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function hidecursordelay_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive integer including 0 (zero) or left blank. It represents the delay in miliseconds. ", $this->plugin_domain ) );
	}

	/**
	 * Returns the specified error message.
	 *
	 * @since  0.1.0
	 * @return \WP_Error
	 */
	public function cursordragspeed_error_message() {

		return new WP_Error( 'broke', __( "Your input didn't pass validation. This value must be a positive number with max two decimal places or left blank. Please review its placeholder.", $this->plugin_domain ) );
	}

}
