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
 * Checkboxes field class.
 */
class CheckboxSet extends AbstractElement {

	/**
	 * Array of checkbox input elements.
	 *
	 * @var array
	 */
	protected $checkboxes = [];

	/**
	 * Array of checked values.
	 *
	 * @var array
	 */
	protected $checked = [];

	/**
	 * Fieldset legend.
	 *
	 * @var string
	 */
	protected $legend = null;

	/**
	 * Constructor
	 *
	 * Instantiate a fieldset of checkbox input form elements.
	 *
	 * @param  string       $name name of the field.
	 * @param  array        $values checkboxes values.
	 * @param  string|array $checked whether it's checked or not.
	 * @param  string       $indent indentation level.
	 * @param  string       $taxonomy taxonomy id if needed to retrieve terms.
	 */
	public function __construct( $name, array $values, $checked = null, $indent = null, $taxonomy = false ) {
		parent::__construct( 'div' );

		$this->setName( $name );
		$this->setAttribute( 'class', 'checkbox-fieldset' );

		if ( null !== $checked ) {
			$this->setValue( $checked );
		}

		if ( null !== $indent ) {
			$this->setIndent( $indent );
		}

		if ( ! empty( $taxonomy ) ) {
			$values = $this->getTaxonomyTermsValues( $taxonomy );
		}

		// Create the checkbox elements and related span elements.
		$i = null;

		foreach ( $values as $k => $v ) {
			$checkbox = new Input\Checkbox( $name . '[]', null, $indent );
			$checkbox->setAttributes(
				[
					'class' => 'custom-control-input',
					'id'    => ( $name . $i ),
					'value' => $k,
				]
			);

			// Determine if the current radio element is checked.
			if ( in_array( $k, $this->checked ) ) {
				$checkbox->check();
			}

			$container = new Child( 'div' );

			if ( null !== $indent ) {
				$container->setIndent( $indent );
			}

			$label = new Child( 'label' );
			$label->setAttribute( 'class', 'custom-control-label' );
			$label->setAttribute( 'for', ( $name . $i ) );
			$label->setNodeValue( $v );

			$container->setAttribute( 'class', 'custom-control custom-checkbox' );
			$container->addChildren( [ $checkbox, $label ] );

			$this->addChildren( [ $container ] );
			$this->checkboxes[] = $checkbox;

			$i++;
		}
	}

	/**
	 * Set whether the form element is disabled.
	 *
	 * @param  boolean $disabled yes or no.
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
	 * Set whether the form element is readonly.
	 *
	 * @param  boolean $readonly true or false.
	 * @return CheckboxSet
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
	 * Set an attribute for the input checkbox elements.
	 *
	 * @param  string $a attribute name.
	 * @param  string $v attribute value.
	 * @return Child
	 */
	public function setCheckboxAttribute( $a, $v ) {
		foreach ( $this->checkboxes as $checkbox ) {
			$checkbox->setAttribute( $a, $v );
			if ( $a == 'tabindex' ) {
				$v++;
			}
		}
		return $this;
	}

	/**
	 * Set an attribute or attributes for the input checkbox elements.
	 *
	 * @param  array $a list of attributes to set.
	 * @return Child
	 */
	public function setCheckboxAttributes( array $a ) {
		foreach ( $this->checkboxes as $checkbox ) {
			$checkbox->setAttributes( $a );
			if ( isset( $a['tabindex'] ) ) {
				$a['tabindex']++;
			}
		}
		return $this;
	}


	/**
	 * Set the checked value of the checkbox form elements.
	 *
	 * @param  $value value to set.
	 * @return CheckboxSet
	 */
	public function setValue( $value ) {
		$this->checked = ( ! is_array( $value ) ) ? [ $value ] : $value;

		if ( ( count( $this->checked ) > 0 ) && ( $this->hasChildren() ) ) {
			foreach ( $this->childNodes as $child ) {
				if ( $child instanceof Child && $child->hasChildren() ) {
					foreach ( $child->childNodes as $checkboxNode ) {
						if ( $checkboxNode instanceof Input\Checkbox ) {
							if ( in_array( $checkboxNode->getValue(), $this->checked ) ) {
								$checkboxNode->check();
							} else {
								$checkboxNode->uncheck();
							}
						}
					}
				}
			}
		}
		return $this;
	}

	/**
	 * Reset the value of the form element.
	 *
	 * @return CheckboxSet
	 */
	public function resetValue() {
		$this->checked = null;
		foreach ( $this->childNodes as $child ) {
			if ( $child instanceof Input\Checkbox ) {
				$child->uncheck();
			}
		}
		return $this;
	}

	/**
	 * Get checkbox form element checked value.
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->checked;
	}

	/**
	 * Set the checked value.
	 *
	 * @param  mixed $checked
	 * @return CheckboxSet
	 */
	public function setChecked( $checked ) {
		return $this->setValue( $checked );
	}

	/**
	 * Get the checked value.
	 *
	 * @return string
	 */
	public function getChecked() {
		return $this->getValue();
	}

	/**
	 * Method to set fieldset legend.
	 *
	 * @param  string $legend
	 * @return CheckboxSet
	 */
	public function setLegend( $legend ) {
		$this->legend = $legend;
		return $this;
	}

	/**
	 * Method to get fieldset legend.
	 *
	 * @return string
	 */
	public function getLegend() {
		return $this->legend;
	}

	/**
	 * Validate the form element object.
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

		return ( count( $this->errors ) === 0 );
	}

	/**
	 * Render the child and its child nodes.
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

	/**
	 * Get form element object type.
	 *
	 * @return string
	 */
	public function getType() {
		if ( ! empty( $this->getTaxonomy() ) ) {
			return 'term-checklist';
		}
		return 'multicheckbox';
	}

}
