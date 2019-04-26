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
 * Button field class.
 */
class Button extends AbstractElement {

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
		parent::__construct( 'button', $value );
		$this->setAttributes(
			[
				'name' => $name,
				'id'   => $name,
			]
		);
		if ( strtolower( $name ) == 'submit' ) {
			$this->setAttribute( 'type', 'submit' );
		} elseif ( strtolower( $name ) == 'reset' ) {
			$this->setAttribute( 'type', 'reset' );
		} else {
			$this->setAttribute( 'type', 'button' );
		}
		$this->setName( $name );
		if ( null !== $value ) {
			$this->setValue( $value );
		}
		if ( null !== $indent ) {
			$this->setIndent( $indent );
		}
	}

	/**
	 * Set the value of the form button element object.
	 *
	 * @param  mixed $value
	 * @return Button
	 */
	public function setValue( $value ) {
		$this->setNodeValue( $value );
		return $this;
	}

	/**
	 * Reset the value of the form element.
	 *
	 * @return Button
	 */
	public function resetValue() {
		$this->setNodeValue( '' );
		return $this;
	}

	/**
	 * Get form element object type.
	 *
	 * @return string
	 */
	public function getType() {
		return 'button';
	}

	/**
	 * Get the value of the form button element object.
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->getNodeValue();
	}

	/**
	 * Validate the form element object.
	 *
	 * @return boolean
	 */
	public function validate() {
		return ( count( $this->errors ) === 0 );
	}
}
