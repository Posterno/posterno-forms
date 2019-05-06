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

use PNO\Dom\Child;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Radio buttons class.
 */
class RadioSet extends AbstractElement {

	/**
	 * Array of radio input elements
	 *
	 * @var array
	 */
	protected $radios = [];

	/**
	 * Array of checked values
	 *
	 * @var string
	 */
	protected $checked = null;

	/**
	 * Fieldset legend
	 *
	 * @var string
	 */
	protected $legend = null;

	/**
	 * Constructor
	 *
	 * Instantiate the radio input form elements
	 *
	 * @param  string $name
	 * @param  array  $values
	 * @param  string $checked
	 * @param  string $indent
	 */
	public function __construct( $name, array $values, $checked = null, $indent = null ) {
		parent::__construct( 'div' );

		$this->setName( $name );
		$this->setAttribute( 'class', 'radio-fieldset' );

		if ( null !== $checked ) {
			$this->setValue( $checked );
		}

		if ( null !== $indent ) {
			$this->setIndent( $indent );
		}

		// Create the radio elements and related span elements.
		$i = null;
		foreach ( $values as $k => $v ) {
			$radio = new Input\Radio( $name, null, $indent );
			$radio->setAttributes(
				[
					'class' => 'custom-control-input',
					'id'    => ( $name . $i ),
					'value' => $k,
				]
			);

			// Determine if the current radio element is checked.
			if ( ( null !== $this->checked ) && ( $k == $this->checked ) ) {
				$radio->check();
			}

			$container = new Child( 'div' );

			if ( null !== $indent ) {
				$container->setIndent( $indent );
			}

			$label = new Child( 'label' );
			$label->setAttribute( 'class', 'custom-control-label' );
			$label->setAttribute( 'for', ( $name . $i ) );
			$label->setNodeValue( $v );

			$container->setAttribute( 'class', 'custom-control custom-radio' );
			$container->addChildren( [ $radio, $label ] );

			$this->addChildren( [ $container ] );
			$this->radios[] = $radio;
			$i++;
		}
	}

	/**
	 * Set whether the form element is disabled
	 *
	 * @param  boolean $disabled
	 * @return Select
	 */
	public function setDisabled( $disabled ) {
		if ( $disabled ) {
			foreach ( $this->childNodes as $childNode ) {
				$childNode->setAttribute( 'disabled', 'disabled' );
			}
		} else {
			foreach ( $this->childNodes as $childNode ) {
				$childNode->removeAttribute( 'disabled' );
			}
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
			foreach ( $this->childNodes as $childNode ) {
				$childNode->setAttribute( 'readonly', 'readonly' );
				$childNode->setAttribute( 'onclick', 'return false;' );
			}
		} else {
			foreach ( $this->childNodes as $childNode ) {
				$childNode->removeAttribute( 'readonly' );
				$childNode->removeAttribute( 'onclick' );
			}
		}

		return parent::setReadonly( $readonly );
	}

	/**
	 * Set an attribute for the input radio elements
	 *
	 * @param  string $a
	 * @param  string $v
	 * @return Child
	 */
	public function setRadioAttribute( $a, $v ) {
		foreach ( $this->radios as $radio ) {
			$radio->setAttribute( $a, $v );
			if ( $a == 'tabindex' ) {
				$v++;
			}
		}
		return $this;
	}

	/**
	 * Set an attribute or attributes for the input radio elements
	 *
	 * @param  array $a
	 * @return Child
	 */
	public function setRadioAttributes( array $a ) {
		foreach ( $this->radios as $radio ) {
			$radio->setAttributes( $a );
			if ( isset( $a['tabindex'] ) ) {
				$a['tabindex']++;
			}
		}
		return $this;
	}

	/**
	 * Set the checked value of the radio form elements
	 *
	 * @param  mixed $value
	 * @return RadioSet
	 */
	public function setValue( $value ) {
		$this->checked = $value;

		if ( ( null !== $this->checked ) && ( $this->hasChildren() ) ) {
			foreach ( $this->childNodes as $child ) {
				if ( $child instanceof Input\Radio ) {
					if ( $child->getValue() == $this->checked ) {
						$child->check();
					} else {
						$child->uncheck();
					}
				}
			}
		}
		return $this;
	}

	/**
	 * Reset the value of the form element
	 *
	 * @return RadioSet
	 */
	public function resetValue() {
		$this->checked = null;
		foreach ( $this->childNodes as $child ) {
			if ( $child instanceof Input\Radio ) {
				$child->uncheck();
			}
		}
		return $this;
	}

	/**
	 * Get radio form element checked value
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->checked;
	}

	/**
	 * Get form element object type
	 *
	 * @return string
	 */
	public function getType() {
		return 'radio';
	}

	/**
	 * Set the checked value
	 *
	 * @param  mixed $checked
	 * @return RadioSet
	 */
	public function setChecked( $checked ) {
		return $this->setValue( $checked );
	}

	/**
	 * Get the checked value
	 *
	 * @return string
	 */
	public function getChecked() {
		return $this->getValue();
	}

	/**
	 * Method to set fieldset legend
	 *
	 * @param  string $legend
	 * @return RadioSet
	 */
	public function setLegend( $legend ) {
		$this->legend = $legend;
		return $this;
	}

	/**
	 * Method to get fieldset legend
	 *
	 * @return string
	 */
	public function getLegend() {
		return $this->legend;
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
			$this->errors[] = sprintf( esc_html__( '%s is a required field.', 'posterno' ), $this->getLabel() );
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

	/**
	 * Render the child and its child nodes
	 *
	 * @param  int     $depth
	 * @param  string  $indent
	 * @param  boolean $inner
	 * @return string
	 */
	public function render( $depth = 0, $indent = null, $inner = false ) {
		if ( ! empty( $this->legend ) ) {
			$this->addChild( new Child( 'legend', $this->legend ) );
		}
		return parent::render( $depth, $indent, $inner );
	}

}
