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
 * Checkbox field class.
 */
class Checkbox extends Element\Input {

	/**
	 * Constructor
	 *
	 * Instantiate the text input form element
	 *
	 * @param  string $name
	 * @param  string $value
	 * @param  string $indent
	 */
	public function __construct( $name, $value = null, $indent = null ) {
		parent::__construct( $name, 'checkbox', $value, $indent );
	}
	/**
	 * Set the value of the form input element object
	 *
	 * @param  mixed $value
	 * @return Checkbox
	 */
	public function setValue( $value ) {
		if ( $value == $this->getAttribute( 'value' ) ) {
			$this->check();
		} else {
			$this->uncheck();
		}
		return $this;
	}

	/**
	 * Reset the value of the form element
	 *
	 * @return Checkbox
	 */
	public function resetValue() {
		$this->uncheck();
		return $this;
	}

	/**
	 * Set the checkbox to checked
	 *
	 * @return Checkbox
	 */
	public function check() {
		$this->setAttribute( 'checked', 'checked' );
		return $this;
	}

	/**
	 * Set the checkbox to checked
	 *
	 * @return Checkbox
	 */
	public function uncheck() {
		$this->removeAttribute( 'checked' );
		return $this;
	}

	/**
	 * Determine if the checkbox value is checked
	 *
	 * @return boolean
	 */
	public function isChecked() {
		return ( $this->getAttribute( 'checked' ) === 'checked' );
	}

}
