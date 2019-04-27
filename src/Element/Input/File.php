<?php
/**
 * Forms generator class for Posterno.
 *
 * @package     posterno
 * @copyright   Copyright (c) 2009-2019 NOLA Interactive, LLC, Copyright (c) 2019, Sematico, LTD.
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

/**
 * @namespace
 */
namespace PNO\Form\Element\Input;

use PNO\Form\Element;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * File field class.
 */
class File extends Element\Input {

	/**
	 * Constructor
	 *
	 * Instantiate the file input form element
	 *
	 * @param  string $name
	 * @param  string $value
	 * @param  string $indent
	 */
	public function __construct( $name, $value = null, $indent = null ) {
		parent::__construct( $name, 'file', $value, $indent );
	}

	/**
	 * Validate the form element object
	 *
	 * @return boolean
	 */
	public function validate() {
		if ( ( $_FILES ) && ( isset( $_FILES[ $this->name ]['name'] ) ) ) {
			$value = $_FILES[ $this->name ]['name'];
			$size  = $_FILES[ $this->name ]['size'];
		} else {
			$value = null;
			$size  = null;
		}

		// Check if the element is required
		if ( ( $this->required ) && empty( $value ) ) {
			$this->errors[] = sprintf( esc_html__( '%s is a required field.', 'posterno' ), $this->getLabel() );
		}

		// Check field validators
		if ( count( $this->validators ) > 0 ) {
			foreach ( $this->validators as $validator ) {
				if ( $validator instanceof \PNO\Validator\ValidatorInterface ) {
					$class = get_class( $validator );
					if ( ( null !== $size ) &&
						( ( 'PNO\Validator\LessThanEqual' == $class ) || ( 'PNO\Validator\GreaterThanEqual' == $class ) ||
						 ( 'PNO\Validator\LessThan' == $class ) || ( 'PNO\Validator\GreaterThan' == $class ) ) ) {
						if ( ! $validator->evaluate( $size ) ) {
							$this->errors[] = $validator->getMessage();
						}
					} else {
						if ( ! $validator->evaluate( $value ) ) {
							$this->errors[] = $validator->getMessage();
						}
					}
				} elseif ( is_callable( $validator ) ) {
					$result = call_user_func_array( $validator, [ $value ] );
					if ( null !== $result ) {
						$this->errors[] = $result;
					}
				}
			}
		}

		return ( count( $this->errors ) == 0 );
	}

}
