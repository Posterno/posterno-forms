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
 * Abstract representation of a dropdown field.
 */
abstract class AbstractSelect extends AbstractElement {

	/**
	 * Selected value(s)
	 *
	 * @var mixed
	 */
	protected $selected = null;

	/**
	 * Set whether the form element is required
	 *
	 * @param  boolean $required
	 * @return Select
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
	 * @return Select
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
	 * @return Select
	 */
	public function setReadonly( $readonly ) {
		if ( $readonly ) {
			$this->setAttribute( 'readonly', 'readonly' );
			foreach ( $this->childNodes as $childNode ) {
				if ( $childNode->getAttribute( 'selected' ) != 'selected' ) {
					$childNode->setAttribute( 'disabled', 'disabled' );
				} else {
					$childNode->setAttribute( 'readonly', 'readonly' );
				}
			}
		} else {
			$this->removeAttribute( 'readonly' );
			foreach ( $this->childNodes as $childNode ) {
				$childNode->removeAttribute( 'disabled' );
				$childNode->removeAttribute( 'readonly' );
			}
		}
		return parent::setReadonly( $readonly );
	}

	/**
	 * Get form element object type
	 *
	 * @return string
	 */
	public function getType() {
		return 'select';
	}

	/**
	 * Get select form element selected value
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->selected;
	}

	/**
	 * Get select form element selected value (alias)
	 *
	 * @return mixed
	 */
	public function getSelected() {
		return $this->getValue();
	}

	/**
	 * Get select options
	 *
	 * @return array
	 */
	public function getOptions() {
		$options = [];
		foreach ( $this->childNodes as $child ) {
			if ( $child instanceof Select\Option ) {
				$options[] = $child;
			} elseif ( $child instanceof Select\Optgroup ) {
				foreach ( $child->getChildren() as $c ) {
					if ( $c instanceof Select\Option ) {
						$options[] = $c;
					}
				}
			}
		}
		return $options;
	}

	/**
	 * Get select options as array
	 *
	 * @return array
	 */
	public function getOptionsAsArray() {
		$options      = $this->getOptions();
		$optionsArray = [];
		foreach ( $options as $option ) {
			$optionsArray[ $option->getValue() ] = $option->getNodeValue();
		}
		return $optionsArray;
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
			$this->errors[] = esc_html__( 'This field is required.', 'posterno' );
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

	/**
	 * Set the select element as multiple
	 *
	 * @param  string|array $values
	 * @param  string       $xmlFile
	 * @return array
	 */
	public static function parseValues( $values ) {

		$parsedValues = null;

		if ( is_array( $values ) ) {
			$parsedValues = $values;
		}

		return $parsedValues;
	}

}
