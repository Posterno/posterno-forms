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
namespace PNO\Form\Element;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing location field class.
 */
class ListingTags extends Input {

	/**
	 * Initialize the editor.
	 *
	 * @param string $name field configuration.
	 * @param string $value field configuration.
	 * @param string $indent field configuration.
	 */
	public function __construct( $name, $value = null, $indent = null ) {
		parent::__construct( $name, $value = null, $indent = null );
	}

	/**
	 * Get form element object type
	 *
	 * @return string
	 */
	public function getType() {
		return 'listing-tags';
	}

	/**
	 * Render the field.
	 *
	 * @return string
	 */
	public function render( $depth = 0, $indent = null, $inner = false ) {

		ob_start();

		posterno()->templates
			->set_template_data(
				[
					'field' => $this,
				]
			)
			->get_template_part( 'form-fields/listing-tags-field' );

		return ob_get_clean();

	}

	/**
	 * Validation methods.
	 *
	 * @return int
	 */
	public function validate() {

		if ( count( $this->validators ) > 0 ) {
			foreach ( $this->validators as $validator ) {
				if ( $validator instanceof \PNO\Validator\ValidatorInterface ) {

					$class = get_class( $validator );

					$termsAmountValidators = [
						'PNO\Validator\LessThanEqual',
						'PNO\Validator\GreaterThanEqual',
						'PNO\Validator\LessThan',
						'PNO\Validator\GreaterThan',
					];

					$amountSubmitted = count( json_decode( stripslashes( $this->getValue() ) ) );

					if ( in_array( $class, $termsAmountValidators, true ) ) {
						if ( ! $validator->evaluate( $amountSubmitted ) ) {
							$this->errors[] = $validator->getMessage();
						}
					} else {
						if ( ! $validator->evaluate( $this->getValue() ) ) {
							$this->errors[] = $validator->getMessage();
						}
					}
				} elseif ( is_callable( $validator ) ) {
					$result = call_user_func_array( $validator, [ $this ] );
					if ( null !== $result ) {
						$this->errors[] = $result;
					}
				}
			}
		}

		return ( count( $this->errors ) == 0 );

	}

}
