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
 * Number field class.
 */
class Number extends Element\Input {

	/**
	 * Constructor
	 *
	 * Instantiate the number input form element
	 *
	 * @param  string $name
	 * @param  int    $min
	 * @param  int    $max
	 * @param  string $value
	 * @param  string $indent
	 */
	public function __construct( $name, $min, $max, $value = null, $indent = null ) {
		parent::__construct( $name, 'number', $value, $indent );
		$this->setAttributes(
			[
				'min' => $min,
				'max' => $max,
			]
		);
	}

}
