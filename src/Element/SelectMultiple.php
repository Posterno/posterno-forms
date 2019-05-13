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
 * Multiselect field class.
 */
class SelectMultiple extends AbstractSelect {

	/**
	 * Constructor
	 *
	 * Instantiate the select form element object
	 *
	 * @param  string       $name
	 * @param  string|array $values
	 * @param  string|array $selected
	 * @param  string       $xmlFile
	 * @param  string       $indent
	 * @param  string       $taxonomy
	 */
	public function __construct( $name, $values, $selected = null, $xmlFile = null, $indent = null, $taxonomy = false ) {
		parent::__construct( 'select' );

		$this->setName( $name );
		$this->setAttributes(
			[
				'name'     => $name . '[]',
				'id'       => $name,
				'multiple' => 'multiple',
			]
		);

		if ( null !== $indent ) {
			$this->setIndent( $indent );
		}

		$values = self::parseValues( $values, $xmlFile );

		if ( ! empty( $taxonomy ) ) {
			$this->setTaxonomy( $taxonomy );
			$values = $this->getTaxonomyTermsValues( $taxonomy );
		}

		// Create the child option elements.
		foreach ( $values as $k => $v ) {
			if ( is_array( $v ) ) {
				$optGroup = new Select\Optgroup();
				if ( null !== $indent ) {
					$optGroup->setIndent( $indent );
				}
				$optGroup->setAttribute( 'label', $k );
				foreach ( $v as $ky => $vl ) {
					$option = new Select\Option( $ky, $vl );
					if ( null !== $indent ) {
						$option->setIndent( $indent );
					}

					// Determine if the current option element is selected.
					if ( is_array( $this->selected ) && in_array( $ky, $this->selected, true ) ) {
						$option->select();
					}
					$optGroup->addChild( $option );
				}
				$this->addChild( $optGroup );
			} else {
				$option = new Select\Option( $k, $v );
				if ( null !== $indent ) {
					$option->setIndent( $indent );
				}

				// Determine if the current option element is selected.
				if ( is_array( $this->selected ) && in_array( $k, $this->selected, true ) ) {
					$option->select();
				}
				$this->addChild( $option );
			}
		}

		if ( null !== $selected ) {
			$this->setValue( $selected );
		} else {
			$this->selected = [];
		}
	}

	/**
	 * Set the selected value of the select form element
	 *
	 * @param  mixed $value
	 * @return SelectMultiple
	 */
	public function setValue( $value ) {
		$this->selected = ( ! is_array( $value ) ) ? [ $value ] : $value;

		if ( $this->hasChildren() ) {
			foreach ( $this->childNodes as $child ) {
				if ( $child instanceof Select\Option ) {
					if ( in_array( $child->getValue(), $this->selected ) ) {
						$child->select();
					} else {
						$child->deselect();
					}
				} elseif ( $child instanceof Select\Optgroup ) {
					$options = $child->getOptions();
					foreach ( $options as $option ) {
						if ( in_array( $option->getValue(), $this->selected ) ) {
							$option->select();
						} else {
							$option->deselect();
						}
					}
				}
			}
		}

		return $this;
	}

	/**
	 * Reset the value of the form element
	 *
	 * @return SelectMultiple
	 */
	public function resetValue() {
		$this->selected = [];

		if ( $this->hasChildren() ) {
			foreach ( $this->childNodes as $child ) {
				if ( $child instanceof Select\Option ) {
					$child->deselect();
				} elseif ( $child instanceof Select\Optgroup ) {
					$options = $child->getOptions();
					foreach ( $options as $option ) {
						$option->deselect();
					}
				}
			}
		}

		return $this;
	}

	/**
	 * Get form element object type.
	 *
	 * @return string
	 */
	public function getType() {
		if ( ! empty( $this->getTaxonomy() ) ) {
			return 'term-multiselect';
		}
		return 'multiselect';
	}

}
