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
 * Price input field class.
 */
class Price extends Element\Input {

	/**
	 * Constructor
	 *
	 * Instantiate the price input form element
	 *
	 * @param string $name
	 * @param string $value
	 * @param string $indent
	 */
	public function __construct( $name, $value = null, $indent = null ) {
		parent::__construct( $name, 'price', $value, $indent );
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
			->get_template_part( 'form-fields/pricing-field' );

		return ob_get_clean();

	}
}
