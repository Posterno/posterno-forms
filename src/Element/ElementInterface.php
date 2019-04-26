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
 * Form element interface class.
 */
interface ElementInterface {

	/**
	 * Set the name of the form element.
	 *
	 * @param  string $name name of the element.
	 * @return ElementInterface
	 */
	public function setName( $name );

	/**
	 * Set the value of the form element.
	 *
	 * @param  mixed $value value of the element.
	 * @return ElementInterface
	 */
	public function setValue( $value );

	/**
	 * Reset the value of the form element.
	 *
	 * @return ElementInterface
	 */
	public function resetValue();

	/**
	 * Set the label of the form element.
	 *
	 * @param  mixed $label label of the element.
	 * @return ElementInterface
	 */
	public function setLabel( $label );

	/**
	 * Set the attributes of the label of the form element.
	 *
	 * @param  array $attribs attributes list.
	 * @return ElementInterface
	 */
	public function setLabelAttributes( array $attribs );

	/**
	 * Set whether the form element is required.
	 *
	 * @param  boolean $required is it required or not.
	 * @return mixed
	 */
	public function setRequired( $required );

	/**
	 * Set whether the form element is disabled.
	 *
	 * @param  boolean $disabled is it disabled or not.
	 * @return mixed
	 */
	public function setDisabled( $disabled );

	/**
	 * Set whether the form element is readonly.
	 *
	 * @param  boolean $readonly is it readonly or not.
	 * @return mixed
	 */
	public function setReadonly( $readonly );

	/**
	 * Set error to display before the element.
	 *
	 * @param  boolean $pre yes or no.
	 * @return ElementInterface
	 */
	public function setErrorPre( $pre );

	/**
	 * Determine if error to display before the element.
	 *
	 * @return boolean
	 */
	public function isErrorPre();

	/**
	 * Set validators.
	 *
	 * @param  array $validators list of validators for the element.
	 * @return ElementInterface
	 */
	public function setValidators( array $validators = [] );

	/**
	 * Clear errors.
	 *
	 * @return ElementInterface
	 */
	public function clearErrors();

	/**
	 * Get form element name.
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get form element object type.
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Get form element value.
	 *
	 * @return mixed
	 */
	public function getValue();

	/**
	 * Get form element label.
	 *
	 * @return string
	 */
	public function getLabel();

	/**
	 * Get the attributes of the form element label.
	 *
	 * @return array
	 */
	public function getLabelAttributes();

	/**
	 * Get validators.
	 *
	 * @return array
	 */
	public function getValidators();

	/**
	 * Get whether the form element is required.
	 *
	 * @return boolean
	 */
	public function isRequired();

	/**
	 * Get whether the form element is disabled.
	 *
	 * @return boolean
	 */
	public function isDisabled();

	/**
	 * Get whether the form element is readonly.
	 *
	 * @return boolean
	 */
	public function isReadonly();

	/**
	 * Get whether the form element object is a button.
	 *
	 * @return boolean
	 */
	public function isButton();

	/**
	 * Get form element errors.
	 *
	 * @return array
	 */
	public function getErrors();

	/**
	 * Get if form element has errors.
	 *
	 * @return array
	 */
	public function hasErrors();

	/**
	 * Add a validator the form element.
	 *
	 * @param  mixed $validator validator to add.
	 * @return ElementInterface
	 */
	public function addValidator( $validator );

	/**
	 * Validate the form element.
	 *
	 * @throws Exception
	 * @return boolean
	 */
	public function validate();

}
