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
 * Textarea field class.
 */
class Textarea extends AbstractElement {

	/**
	 * Constructor
	 *
	 * Instantiate the textarea form element
	 *
	 * @param  string $name
	 * @param  string $value
	 * @param  string $indent
	 */
	public function __construct( $name, $value = null, $indent = null ) {
		parent::__construct( 'textarea', $value );

		$this->setAttributes(
			[
				'name' => $name,
				'id'   => $name,
			]
		);
		$this->setName( $name );
		if ( null !== $indent ) {
			$this->setIndent( $indent );
		}
	}

	/**
	 * Set whether the form element is required
	 *
	 * @param  boolean $required
	 * @return Textarea
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
	 * Set whether the form element is disabled
	 *
	 * @param  boolean $disabled
	 * @return Textarea
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
	 * Set whether the form element is readonly
	 *
	 * @param  boolean $readonly
	 * @return Textarea
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
	 * Set the value of the form textarea element object
	 *
	 * @param  mixed $value
	 * @return Textarea
	 */
	public function setValue( $value ) {
		$this->setNodeValue( $value );
		return $this;
	}

	/**
	 * Reset the value of the form element
	 *
	 * @return Textarea
	 */
	public function resetValue() {
		$this->setNodeValue( '' );
		return $this;
	}

	/**
	 * Get form element object type
	 *
	 * @return string
	 */
	public function getType() {
		return 'textarea';
	}

	/**
	 * Get the value of the form textarea element object
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->getNodeValue();
	}

	/**
	 * Validate the form element object
	 *
	 * @return boolean
	 */
	public function validate() {
		$value = $this->getValue();

		// Check if the element is required
		if ( ( $this->required ) && empty( $value ) ) {
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

		return ( count( $this->errors ) === 0 );
	}

}
