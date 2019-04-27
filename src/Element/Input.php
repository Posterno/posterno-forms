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
 * Form input element class.
 */
class Input extends AbstractElement {

	/**
	 * Constructor
	 *
	 * Instantiate the form input element, defaults to text.
	 *
	 * @param  string $name element name.
	 * @param  string $type input type.
	 * @param  string $value field value.
	 * @param  string $indent indentation.
	 */
	public function __construct( $name, $type = 'text', $value = null, $indent = null ) {
		parent::__construct( 'input' );
		$this->setName( $name );
		$this->setAttributes(
			[
				'type'  => $type,
				'name'  => $name,
				'id'    => $name,
				'value' => $value,
			]
		);
		if ( null !== $indent ) {
			$this->setIndent( $indent );
		}
	}

	/**
	 * Set whether the form element is required.
	 *
	 * @param  boolean $required yes or no.
	 * @return Input
	 */
	public function setRequired( $required ) {
		if ( $required ) {
			$this->setAttribute( 'required', 'required' );
		} else {
			$this->removeAttribute( 'required' );
		}
		return parent::setRequired( $required );
	}

	/**
	 * Set whether the form element is disabled.
	 *
	 * @param  boolean $disabled yes or no.
	 * @return Input
	 */
	public function setDisabled( $disabled ) {
		if ( $disabled ) {
			$this->setAttribute( 'disabled', 'disabled' );
		} else {
			$this->removeAttribute( 'disabled' );
		}
		return parent::setDisabled( $disabled );
	}

	/**
	 * Set whether the form element is readonly.
	 *
	 * @param  boolean $readonly yes or no.
	 * @return Input
	 */
	public function setReadonly( $readonly ) {
		if ( $readonly ) {
			$this->setAttribute( 'readonly', 'readonly' );
		} else {
			$this->removeAttribute( 'readonly' );
		}
		return parent::setReadonly( $readonly );
	}

	/**
	 * Set the value of the form input element object.
	 *
	 * @param  mixed $value value to assign.
	 * @return Input
	 */
	public function setValue( $value ) {
		$this->setAttribute( 'value', $value );
		return $this;
	}

	/**
	 * Reset the value of the form element.
	 *
	 * @return Input
	 */
	public function resetValue() {
		$this->setAttribute( 'value', '' );
		return $this;
	}

	/**
	 * Get the value of the form input element object.
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->getAttribute( 'value' );
	}

	/**
	 * Get the type of the form input element object.
	 *
	 * @return string
	 */
	public function getType() {
		return $this->getAttribute( 'type' );
	}

	/**
	 * Validate the form element object.
	 *
	 * @return boolean
	 */
	public function validate() {
		$value = $this->getValue();
		// Check if the element is required
		if ( ( $this->required ) && empty( $value ) && ! ( $this->getType() == 'number' && $value === '0' ) ) {
			$this->errors[] = sprintf( esc_html__( '%s is a required field', 'posterno' ), $this->getLabel() );
		}
		// Check field validators
		if ( count( $this->validators ) > 0 ) {
			foreach ( $this->validators as $validator ) {
				if ( $validator instanceof \PNO\Validator\ValidatorInterface ) {
					if ( ! $validator->evaluate( $value ) ) {
						if ( ! in_array( $validator->getMessage(), $this->errors ) ) {
							$this->errors[] = $validator->getMessage();
						}
					}
				} elseif ( is_callable( $validator ) ) {
					$result = call_user_func_array( $validator, [ $value ] );
					if ( null !== $result ) {
						if ( ! in_array( $result, $this->errors ) ) {
							$this->errors[] = $result;
						}
					}
				}
			}
		}
		return ( count( $this->errors ) == 0 );
	}

}
