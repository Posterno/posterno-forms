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
 * Heading field class.
 */
class Heading extends Input {

	/**
	 * Constructor
	 *
	 * Instantiate the button form element.
	 *
	 * @param  string $name button name.
	 * @param  string $value value.
	 * @param  string $indent indentation.
	 */
	public function __construct( $name, $value = null, $indent = null ) {
		parent::__construct( $name, $value = null, $indent = null );
	}

	/**
	 * Get form element object type.
	 *
	 * @return string
	 */
	public function getType() {
		return 'heading';
	}

	/**
	 * Get the value of the form button element object.
	 *
	 * @return string
	 */
	public function getValue() {
		return false;
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
			->get_template_part( 'form-fields/heading-field' );

		return ob_get_clean();

	}

}
