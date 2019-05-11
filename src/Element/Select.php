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
 * Select dropdown class.
 */
class Select extends AbstractSelect {

	/**
	 * Constructor
	 *
	 * Instantiate the select form element object
	 *
	 * @param  string       $name
	 * @param  string|array $values
	 * @param  string       $selected
	 * @param  string       $xmlFile
	 * @param  string       $indent
	 * @param string       $taxonomy
	 */
	public function __construct( $name, $values, $selected = null, $xmlFile = null, $indent = null, $taxonomy = false ) {
		parent::__construct( 'select' );
		$this->setName( $name );
		$this->setAttributes(
			[
				'name' => $name,
				'id'   => $name,
			]
		);

		if ( null !== $selected ) {
			$this->setValue( $selected );
		}
		if ( null !== $indent ) {
			$this->setIndent( $indent );
		}

		$values = self::parseValues( $values, $xmlFile );

		if ( ! empty( $taxonomy ) ) {
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
					if ( ( null !== $this->selected ) && ( $ky == $this->selected ) ) {
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
				if ( ( null !== $this->selected ) && ( $k == $this->selected ) ) {
					$option->select();
				}
				$this->addChild( $option );
			}
		}
	}

	/**
	 * Set the selected value of the select form element
	 *
	 * @param  mixed $value
	 * @return Select
	 */
	public function setValue( $value ) {
		$this->selected = $value;

		if ( $this->hasChildren() ) {
			foreach ( $this->childNodes as $child ) {
				if ( $child instanceof Select\Option ) {
					if ( $child->getValue() == $this->selected ) {
						$child->select();
					} else {
						$child->deselect();
					}
				} elseif ( $child instanceof Select\Optgroup ) {
					$options = $child->getOptions();
					foreach ( $options as $option ) {
						if ( $option->getValue() == $this->selected ) {
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
	 * @return Select
	 */
	public function resetValue() {
		$this->selected = null;

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

}
