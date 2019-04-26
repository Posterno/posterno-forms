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
namespace PNO\Form\Element\Select;

use PNO\Dom\Child;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Option tag field class.
 */
class Option extends Child {

	/**
	 * Constructor
	 *
	 * Instantiate the option element object
	 *
	 * @param  string $value
	 * @param  string $nodeValue
	 * @param  array  $options
	 */
	public function __construct( $value, $nodeValue, array $options = [] ) {
		parent::__construct( 'option', $nodeValue, $options );
		$this->setValue( $value );
	}

	/**
	 * Set the option value
	 *
	 * @param  mixed $value
	 * @return Option
	 */
	public function setValue( $value ) {
		$this->setAttribute( 'value', $value );
		return $this;
	}

	/**
	 * Get the option value
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->getAttribute( 'value' );
	}

	/**
	 * Select the option value
	 *
	 * @return Option
	 */
	public function select() {
		$this->setAttribute( 'selected', 'selected' );
		return $this;
	}

	/**
	 * Deselect the option value
	 *
	 * @return Option
	 */
	public function deselect() {
		$this->removeAttribute( 'selected' );
		return $this;
	}

	/**
	 * Determine if the option value is selected
	 *
	 * @return boolean
	 */
	public function isSelected() {
		return ( $this->getAttribute( 'selected' ) == 'selected' );
	}

}
