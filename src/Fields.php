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
namespace PNO\Form;

use PNO\Form\Element\AbstractElement;
use PNO\Validator;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Form field configuration class.
 */
class Fields {

	/**
	 * Static factory method to create a field element object from a field config array.
	 *
	 * @param  string $name name of the field.
	 * @param  array  $field configuration settings.
	 * @throws Exception When field class is not found or properly configured.
	 * @return Element\AbstractElement
	 */
	public static function create( $name, array $field ) {
		if ( ! isset( $field['type'] ) ) {
			throw new Exception( 'Error: The field type was not set.' );
		}
		$type         = $field['type'];
		$value        = ( isset( $field['value'] ) ) ? $field['value'] : null;
		$values       = ( isset( $field['values'] ) ) ? $field['values'] : [];
		$label        = ( isset( $field['label'] ) ) ? $field['label'] : null;
		$indent       = ( isset( $field['indent'] ) ) ? $field['indent'] : null;
		$checked      = ( isset( $field['checked'] ) ) ? $field['checked'] : null;
		$selected     = ( isset( $field['selected'] ) ) ? $field['selected'] : null;
		$required     = ( isset( $field['required'] ) ) ? $field['required'] : null;
		$disabled     = ( isset( $field['disabled'] ) ) ? $field['disabled'] : null;
		$readonly     = ( isset( $field['readonly'] ) ) ? $field['readonly'] : null;
		$attributes   = ( isset( $field['attributes'] ) ) ? $field['attributes'] : null;
		$validators   = ( isset( $field['validators'] ) ) ? $field['validators'] : null;
		$render       = ( isset( $field['render'] ) ) ? $field['render'] : false;
		$multiple     = ( isset( $field['multiple'] ) ) ? $field['multiple'] : false;
		$min          = ( isset( $field['min'] ) ) ? $field['min'] : false;
		$max          = ( isset( $field['max'] ) ) ? $field['max'] : false;
		$maxSize      = ( isset( $field['max_size'] ) ) ? $field['max_size'] : false;
		$mimeTypes    = ( isset( $field['allowed_mime_types'] ) ) ? $field['allowed_mime_types'] : false;
		$xmlFile      = ( isset( $field['xml'] ) ) ? $field['xml'] : null;
		$hint         = ( isset( $field['hint'] ) ) ? $field['hint'] : null;
		$hintAttribs  = ( isset( $field['hint-attributes'] ) ) ? $field['hint-attributes'] : null;
		$labelAttribs = ( isset( $field['label-attributes'] ) ) ? $field['label-attributes'] : null;
		$errorPre     = ( isset( $field['error'] ) && ( $field['error'] == 'pre' ) );
		// Initialize the form element.
		switch ( strtolower( $type ) ) {
			case 'button':
				$element = new Element\Button( $name, $value, $indent );
				break;
			case 'select':
				$element = new Element\Select( $name, $values, $selected, $xmlFile, $indent );
				break;
			case 'select-multiple':
			case 'multiselect':
				$element = new Element\SelectMultiple( $name, $values, $selected, $xmlFile, $indent );
				break;
			case 'editor':
				$element = new Element\Editor( $name, $value, $indent );
				break;
			case 'textarea':
				$element = new Element\Textarea( $name, $value, $indent );
				break;
			case 'checkbox':
				$element = new Element\Input\Checkbox( $name, $value, $checked, $indent );
				break;
			case 'checkboxset':
			case 'multicheckbox':
				$element = new Element\CheckboxSet( $name, $values, $checked, $indent );
				break;
			case 'radio':
			case 'radioset':
				$element = new Element\RadioSet( $name, $values, $checked, $indent );
				break;
			case 'input-button':
				$element = new Element\Input\Button( $name, $value );
				break;
			case 'number':
				$element = new Element\Input\Number( $name, $min, $max, $value );
				break;
			case 'range':
				$element = new Element\Input\Range( $name, $min, $max, $value );
				break;
			default:
				$name = $multiple === true ? $name . '[]' : $name;
				$class = 'PNO\\Form\\Element\\Input\\' . ucfirst( strtolower( $type ) );
				if ( ! class_exists( $class ) ) {
					throw new Exception( 'Error: "' . ucfirst( strtolower( $type ) ) . '" class for that form element does not exist.' );
				}
				$element = new $class( $name, $value );
				if ( $class == 'PNO\\Form\\Element\\Input\\Password' ) {
					$element->setRenderValue( $render );
				}
		}
		// Set the label.
		if ( null !== $label ) {
			$element->setLabel( $label );
		}
		// Set the label attributes.
		if ( ( null !== $labelAttribs ) && is_array( $labelAttribs ) ) {
			$element->setLabelAttributes( $labelAttribs );
		}
		// Set the hint.
		if ( null !== $hint ) {
			$element->setHint( $hint );
		}
		// Set the hint attributes.
		if ( ( null !== $hintAttribs ) && is_array( $hintAttribs ) ) {
			$element->setHintAttributes( $hintAttribs );
		}
		// Set if required.
		if ( ( null !== $required ) && ( $required ) ) {
			$element->setRequired( $required );
		}
		// Set if disabled.
		if ( ( null !== $disabled ) && ( $disabled ) ) {
			$element->setDisabled( $disabled );
		}
		// Set if readonly.
		if ( ( null !== $readonly ) && ( $readonly ) ) {
			$element->setReadonly( $readonly );
		}
		// Force value if available.
		if ( ! empty( $value ) ) {
			$element->setValue( $value );
		}

		// Set max size.
		if ( ! empty( $maxSize ) && method_exists( $element, 'setMaxSize' ) ) {
			$element->setMaxSize( $maxSize );
		}

		// Set mime types.
		if ( ! empty( $mimeTypes ) && method_exists( $element, 'setMimeTypes' ) ) {
			$element->setMimeTypes( $mimeTypes );
		}

		// Add multiple attribute if file field supports it.
		if ( $element->getType() === 'file' && $multiple === true ) {
			$element->setAttribute( 'multiple', 'multiple' );
		}

		$element->setErrorPre( $errorPre );

		// Set any attributes.
		if ( null !== $attributes ) {
			if ( $element instanceof Element\CheckboxSet ) {
				$element->setCheckboxAttributes( $attributes );
			} elseif ( $element instanceof Element\RadioSet ) {
				$element->setRadioAttributes( $attributes );
			} else {
				$element->setAttributes( $attributes );
			}
		}

		// Set any validators.
		if ( null !== $validators ) {
			if ( is_array( $validators ) ) {
				$element->addValidators( $validators );
			} else {
				$element->addValidator( $validators );
			}
		}

		// Automatically set validators for specific field types.
		self::setRequiredValidators( $element );

		return $element;
	}

	/**
	 * Set required validators for specific field types.
	 *
	 * @param AbstractElement $element field element.
	 * @return void
	 */
	protected static function setRequiredValidators( AbstractElement $element ) {

		if ( $element->getType() === 'file' ) {

			// Verify file size is supported.
			$supportedMaxSize = ! empty( $element->getMaxSize() ) ? $element->getMaxSize() : wp_max_upload_size();
			$maxSize          = new Validator\LessThanEqual( $supportedMaxSize, sprintf( esc_html__( 'Uploaded file exceeds the maximum file size of: %1$s', 'posterno' ), size_format( $supportedMaxSize ) ) );

			$element->addValidator( $maxSize );

		}
	}

}
