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

use PNO\Dom\Child;
use PNO\Form\Element;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Helper class to generate forms for the Posterno plugin.
 */
class Form extends Child implements \ArrayAccess, \Countable, \IteratorAggregate {

	/**
	 * Field fieldsets
	 *
	 * @var array
	 */
	protected $fieldsets = [];

	/**
	 * Form columns
	 *
	 * @var array
	 */
	protected $columns = [];

	/**
	 * Current field fieldset
	 *
	 * @var int
	 */
	protected $current = 0;

	/**
	 * Filters
	 *
	 * @var array
	 */
	protected $filters = [];

	/**
	 * Get things started.
	 *
	 * @param array  $fields fields list.
	 * @param string $action form action.
	 * @param string $method form method.
	 */
	public function __construct( array $fields = null, $action = null, $method = 'post' ) {
		if ( null === $action ) {
			$action = ( isset( $_SERVER['REQUEST_URI'] ) ) ? $_SERVER['REQUEST_URI'] : '#';
		}
		parent::__construct( 'form' );
		$this->setAction( $action );
		$this->setMethod( $method );
		if ( null !== $fields ) {
			$this->addFields( $fields );
		}
	}

	/**
	 * Generate a form form an array.
	 *
	 * @param array  $config config array.
	 * @param string $action form action.
	 * @param string $method form method.
	 * @return Form
	 */
	public static function createFromConfig( array $config, $action = null, $method = 'post' ) {
		$form = new static( null, $action, $method );
		$form->addFieldsFromConfig( $config );
		return $form;
	}

	/**
	 * Create a fieldset from a config array.
	 *
	 * @param array  $config config definition.
	 * @param string $container container node.
	 * @param string $action form action.
	 * @param string $method form method.
	 * @return Form
	 */
	public static function createFromFieldsetConfig( array $config, $container = null, $action = null, $method = 'post' ) {
		$form = new static( null, $action, $method );
		$form->addFieldsetsFromConfig( $config, $container );
		return $form;
	}

	/**
	 * Create a fieldset.
	 *
	 * @param string $legend legend title.
	 * @param string $container container node.
	 * @return Fieldset
	 */
	public function createFieldset( $legend = null, $container = null ) {
		$fieldset = new Fieldset();
		if ( null !== $legend ) {
			$fieldset->setLegend( $legend );
		}
		if ( null !== $container ) {
			$fieldset->setContainer( $container );
		}
		$this->addFieldset( $fieldset );
		$id    = ( null !== $this->getAttribute( 'id' ) ) ?
			$this->getAttribute( 'id' ) . '-fieldset-' . ( $this->current + 1 ) : 'posterno-form-fieldset-' . ( $this->current + 1 );
		$class = ( null !== $this->getAttribute( 'class' ) ) ?
			$this->getAttribute( 'id' ) . '-fieldset' : 'posterno-form-fieldset';
		$fieldset->setAttribute( 'id', $id );
		$fieldset->setAttribute( 'class', $class );
		return $fieldset;
	}

	/**
	 * Set form action.
	 *
	 * @param string $action form action.
	 * @return Form
	 */
	public function setAction( $action ) {
		$this->setAttribute( 'action', str_replace( [ '?captcha=1', '&captcha=1' ], [ '', '' ], $action ) );
		return $this;
	}

	/**
	 * Set form method.
	 *
	 * @param string $method the form method.
	 * @return Form
	 */
	public function setMethod( $method ) {
		$this->setAttribute( 'method', $method );
		return $this;
	}

	/**
	 * Get the form action.
	 *
	 * @return mixed
	 */
	public function getAction() {
		return $this->getAttribute( 'action' );
	}

	/**
	 * Get the form method.
	 *
	 * @return string
	 */
	public function getMethod() {
		return $this->getAttribute( 'method' );
	}

	/**
	 * Set attribute helper.
	 *
	 * @param string $a attribute name.
	 * @param string $v attribute value.
	 * @return Form
	 */
	public function setAttribute( $a, $v ) {
		parent::setAttribute( $a, $v );
		if ( $a == 'id' ) {
			foreach ( $this->fieldsets as $i => $fieldset ) {
				$id = $v . '-fieldset-' . ( $i + 1 );
				$fieldset->setAttribute( 'id', $id );
			}
		} elseif ( $a == 'class' ) {
			foreach ( $this->fieldsets as $i => $fieldset ) {
				$class = $v . '-fieldset';
				$fieldset->setAttribute( 'class', $class );
			}
		}
		return $this;
	}

	/**
	 * Set attributes.
	 *
	 * @param array $a list of attrs.
	 * @return Form
	 */
	public function setAttributes( array $a ) {
		foreach ( $a as $name => $value ) {
			$this->setAttribute( $name, $value );
		}
		return $this;
	}

	/**
	 * Add a fieldset.
	 *
	 * @param Fieldset $fieldset the fieldset object.
	 * @return Form
	 */
	public function addFieldset( Fieldset $fieldset ) {
		$this->fieldsets[] = $fieldset;
		$this->current     = count( $this->fieldsets ) - 1;
		return $this;
	}

	/**
	 * Remove a fieldset.
	 *
	 * @param string $i fieldset index.
	 * @return Form
	 */
	public function removeFieldset( $i ) {
		if ( isset( $this->fieldsets[ (int) $i ] ) ) {
			unset( $this->fieldsets[ (int) $i ] );
		}
		$this->fieldsets = array_values( $this->fieldsets );
		if ( ! isset( $this->fieldsets[ $this->current ] ) ) {
			$this->current = ( count( $this->fieldsets ) > 0 ) ? count( $this->fieldsets ) - 1 : 0;
		}
		return $this;
	}

	/**
	 * Get a fieldset.
	 *
	 * @return Fieldset
	 */
	public function getFieldset() {
		return ( isset( $this->fieldsets[ $this->current ] ) ) ? $this->fieldsets[ $this->current ] : null;
	}

	/**
	 * Add a column to the form.
	 *
	 * @param mixed  $fieldsets field sets list.
	 * @param string $class additional class.
	 * @return Form
	 */
	public function addColumn( $fieldsets, $class = null ) {
		if ( ! is_array( $fieldsets ) ) {
			$fieldsets = [ $fieldsets ];
		}
		foreach ( $fieldsets as $i => $num ) {
			$fieldsets[ $i ] = (int) $num - 1;
		}
		if ( null === $class ) {
			$class = 'posterno-form-column-' . ( count( $this->columns ) + 1 );
		}
		$this->columns[ $class ] = $fieldsets;
		return $this;
	}

	/**
	 * Determine if form has column.
	 *
	 * @param string $class the class to verify.
	 * @return boolean
	 */
	public function hasColumn( $class ) {
		if ( is_numeric( $class ) ) {
			$class = 'posterno-form-column-' . $class;
		}
		return isset( $this->columns[ $class ] );
	}

	/**
	 * Get a specific column.
	 *
	 * @param string $class the class to verify
	 * @return object
	 */
	public function getColumn( $class ) {
		if ( is_numeric( $class ) ) {
			$class = 'posterno-form-column-' . $class;
		}
		return ( isset( $this->columns[ $class ] ) ) ? $this->columns[ $class ] : null;
	}

	/**
	 * Remove a specific column.
	 *
	 * @param string $class the class to remove.
	 * @return Form
	 */
	public function removeColumn( $class ) {
		if ( is_numeric( $class ) ) {
			$class = 'posterno-form-column-' . $class;
		}
		if ( isset( $this->columns[ $class ] ) ) {
			unset( $this->columns[ $class ] );
		}
		return $this;
	}

	/**
	 * Get current.
	 *
	 * @return mixed
	 */
	public function getCurrent() {
		return $this->current;
	}

	/**
	 * Set current.
	 *
	 * @param string $i index of the fieldset.
	 * @return void
	 */
	public function setCurrent( $i ) {
		$this->current = (int) $i;
		if ( ! isset( $this->fieldsets[ $this->current ] ) ) {
			$this->fieldsets[ $this->current ] = $this->createFieldset();
		}
		return $this;
	}

	/**
	 * Get legend assigned to the current fieldset.
	 *
	 * @return string
	 */
	public function getLegend() {
		return ( isset( $this->fieldsets[ $this->current ] ) ) ?
			$this->fieldsets[ $this->current ]->getLegend() : null;
	}

	/**
	 * Set legend to the current fieldset.
	 *
	 * @param string $legend the title.
	 * @return void
	 */
	public function setLegend( $legend ) {
		if ( isset( $this->fieldsets[ $this->current ] ) ) {
			$this->fieldsets[ $this->current ]->setLegend( $legend );
		}
		return $this;
	}

	/**
	 * Add a field to the form.
	 *
	 * @param Element\AbstractElement $field the field to add.
	 * @return Form
	 */
	public function addField( Element\AbstractElement $field ) {
		if ( count( $this->fieldsets ) == 0 ) {
			$this->createFieldset();
		}
		$this->fieldsets[ $this->current ]->addField( $field );
		return $this;
	}

	/**
	 * Add multiple fields to the form.
	 *
	 * @param array $fields the list of fields to add.
	 * @return Form
	 */
	public function addFields( array $fields ) {
		foreach ( $fields as $field ) {
			$this->addField( $field );
		}
		return $this;
	}

	/**
	 * Add a field from a config array.
	 *
	 * @param string $name field name.
	 * @param array  $field field details.
	 * @return Form
	 */
	public function addFieldFromConfig( $name, $field ) {
		$this->addField( Fields::create( $name, $field ) );
		return $this;
	}

	/**
	 * Add fields from a config array.
	 *
	 * @param array $config the fields definition.
	 * @return Form
	 */
	public function addFieldsFromConfig( array $config ) {
		$i = 1;
		foreach ( $config as $name => $field ) {
			if ( is_numeric( $name ) && ! isset( $field[ $name ]['type'] ) ) {
				$fields = [];
				foreach ( $field as $n => $f ) {
					$fields[ $n ] = Fields::create( $n, $f );
				}
				if ( $i > 1 ) {
					$this->fieldsets[ $this->current ]->createGroup();
				}
				$this->fieldsets[ $this->current ]->addFields( $fields );
				$i++;
			} else {
				$this->addField( Fields::create( $name, $field ) );
			}
		}
		return $this;
	}

	/**
	 * Add fieldsets from a config array.
	 *
	 * @param array $fieldsets the list of fieldsets.
	 * @param mixed $container the container node.
	 * @return Form
	 */
	public function addFieldsetsFromConfig( array $fieldsets, $container = null ) {
		foreach ( $fieldsets as $legend => $config ) {
			if ( ! is_numeric( $legend ) ) {
				$this->createFieldset( $legend, $container );
			} else {
				$this->createFieldset( null, $container );
			}
			$this->addFieldsFromConfig( $config );
		}
		return $this;
	}

	/**
	 * Insert a specific field before a specific one.
	 *
	 * @param string                  $name field name to target.
	 * @param Element\AbstractElement $field the field to add.
	 * @return Form
	 */
	public function insertFieldBefore( $name, Element\AbstractElement $field ) {
		foreach ( $this->fieldsets as $fieldset ) {
			if ( $fieldset->hasField( $name ) ) {
				$fieldset->insertFieldBefore( $name, $field );
				break;
			}
		}
		return $this;
	}

	/**
	 * Insert a specific field before a specific one.
	 *
	 * @param string                  $name the field to target.
	 * @param Element\AbstractElement $field the field to add.
	 * @return Form
	 */
	public function insertFieldAfter( $name, Element\AbstractElement $field ) {
		foreach ( $this->fieldsets as $fieldset ) {
			if ( $fieldset->hasField( $name ) ) {
				$fieldset->insertFieldAfter( $name, $field );
				break;
			}
		}
		return $this;
	}

	/**
	 * Count fields available into the form.
	 *
	 * @return string.
	 */
	public function count() {
		$count = 0;
		foreach ( $this->fieldsets as $fieldset ) {
			$count += $fieldset->count();
		}
		return $count;
	}

	/**
	 * Retrieve an array of the fields.
	 *
	 * @return array
	 */
	public function toArray() {
		$fieldValues = [];
		foreach ( $this->fieldsets as $fieldset ) {
			$fieldValues = array_merge( $fieldValues, $fieldset->toArray() );
		}
		return $fieldValues;
	}

	/**
	 * Get a field.
	 *
	 * @param string $name the name of the field.
	 * @return object
	 */
	public function getField( $name ) {
		$namedField = null;
		$fields     = $this->getFields();
		foreach ( $fields as $field ) {
			if ( $field->getName() == $name ) {
				$namedField = $field;
				break;
			}
		}
		return $namedField;
	}

	/**
	 * Get all fields.
	 *
	 * @return array
	 */
	public function getFields() {
		$fields = [];
		foreach ( $this->fieldsets as $fieldset ) {
			$fields = array_merge( $fields, $fieldset->getAllFields() );
		}
		return $fields;
	}

	/**
	 * Remove a field.
	 *
	 * @param string $field the field to remove.
	 * @return Form
	 */
	public function removeField( $field ) {
		foreach ( $this->fieldsets as $fieldset ) {
			if ( $fieldset->hasField( $field ) ) {
				unset( $fieldset[ $field ] );
			}
		}
		return $this;
	}

	/**
	 * Get the value of a field.
	 *
	 * @param string $name the name of the field.
	 * @return mixed
	 */
	public function getFieldValue( $name ) {
		$fieldValues = $this->toArray();
		return ( isset( $fieldValues[ $name ] ) ) ? $fieldValues[ $name ] : null;
	}

	/**
	 * Set a value to a field.
	 *
	 * @param string $name the name of the field.
	 * @param mixed  $value the value to assign.
	 * @return Form
	 */
	public function setFieldValue( $name, $value ) {
		foreach ( $this->fieldsets as $fieldset ) {
			if ( isset( $fieldset[ $name ] ) ) {
				$fieldset[ $name ] = $value;
			}
		}
		return $this;
	}

	/**
	 * Set multiple values to multiple fields.
	 *
	 * @param array $values array of name => value definition.
	 * @return Form
	 */
	public function setFieldValues( array $values ) {
		$fields = $this->toArray();
		foreach ( $fields as $name => $value ) {
			if ( isset( $values[ $name ] ) && ! ( $this->getField( $name )->isButton() ) ) {
				$this->setFieldValue( $name, $values[ $name ] );
			} elseif ( ! ( $this->getField( $name )->isButton() ) ) {
				$this->getField( $name )->resetValue();
			}
		}
		$this->filterValues();
		return $this;
	}

	/**
	 * Add honeypot antispam protection to all forms, automagically.
	 *
	 * @return Form
	 */
	public function addHoneypot() {

	}

	/**
	 * Add WP nonce validation to all forms, automagically.
	 *
	 * @return Form
	 */
	public function addNonceVerification() {

	}

	/**
	 * Get iterator.
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return new \ArrayIterator( $this->toArray() );
	}

	/**
	 * Add a sanitization filter to the form.
	 *
	 * @param Filter\FilterInterface $filter the filter class.
	 * @return Form
	 */
	public function addFilter( Filter\FilterInterface $filter ) {
		$this->filters[] = $filter;
		return $this;
	}

	/**
	 * Add multiple filters to the form.
	 *
	 * @param array $filters the list of filters.
	 * @return Form
	 */
	public function addFilters( array $filters ) {
		foreach ( $filters as $filter ) {
			$this->addFilter( $filter );
		}
		return $this;
	}

	/**
	 * Clear the list of assigned filters.
	 *
	 * @return Form
	 */
	public function clearFilters() {
		$this->filters = [];
		return $this;
	}

	/**
	 * Trigger value filtering.
	 *
	 * @param mixed $value the value to filter.
	 * @return mixed the filtered value.
	 */
	public function filterValue( $value ) {
		if ( $value instanceof Element\AbstractElement ) {
			$name      = $value->getName();
			$type      = $value->getType();
			$realValue = $value->getValue();
		} else {
			$type      = null;
			$name      = null;
			$realValue = $value;
		}
		foreach ( $this->filters as $filter ) {
			$realValue = $filter->filter( $realValue, $type, $name );
		}
		if ( ( $value instanceof Element\AbstractElement ) && ( null !== $realValue ) && ( $realValue != '' ) ) {
			$value->setValue( $realValue );
		}
		return $realValue;
	}

	/**
	 * Filter multiple values.
	 *
	 * @param array $values values to filter.
	 * @return mixed
	 */
	public function filterValues( array $values = null ) {
		if ( null === $values ) {
			$values = $this->getFields();
		}
		foreach ( $values as $key => $value ) {
			$values[ $key ] = $this->filterValue( $value );
		}
		return $values;
	}

	/**
	 * Verify the form has been successfully submitted or not.
	 *
	 * @return boolean
	 */
	public function isValid() {
		$result = true;
		$fields = $this->getFields();
		// Check each element for validators, validate them and return the result.
		foreach ( $fields as $field ) {
			if ( $field->validate() == false ) {
				$result = false;
			}
		}
		return $result;
	}

	/**
	 * Get all errors generated within the form field if any.
	 *
	 * @param string $name the field name.
	 * @return array
	 */
	public function getErrors( $name ) {
		$field  = $this->getField( $name );
		$errors = ( null !== $field ) ? $field->getErrors() : [];
		return $errors;
	}

	/**
	 * Get all errors of all fields.
	 *
	 * @return array
	 */
	public function getAllErrors() {
		$errors = [];
		$fields = $this->getFields();
		foreach ( $fields as $name => $field ) {
			if ( $field->hasErrors() ) {
				$errors[ str_replace( '[]', '', $field->getName() ) ] = $field->getErrors();
			}
		}
		return $errors;
	}

	/**
	 * Reset the form.
	 *
	 * @return Form
	 */
	public function reset() {
		$fields = $this->getFields();
		foreach ( $fields as $field ) {
			$field->resetValue();
		}
		return $this;
	}

	public function clearTokens() {
		// Start a session.
		if ( session_id() == '' ) {
			session_start();
		}
		if ( isset( $_SESSION['posterno_csrf'] ) ) {
			unset( $_SESSION['posterno_csrf'] );
		}
		if ( isset( $_SESSION['posterno_captcha'] ) ) {
			unset( $_SESSION['posterno_captcha'] );
		}
		return $this;
	}

	/**
	 * Prepare the form for display.
	 *
	 * @return Form
	 */
	public function prepare() {
		if ( null === $this->getAttribute( 'id' ) ) {
			$this->setAttribute( 'id', 'posterno-form' );
		}
		if ( null === $this->getAttribute( 'class' ) ) {
			$this->setAttribute( 'class', 'posterno-form' );
		}
		if ( count( $this->columns ) > 0 ) {
			foreach ( $this->columns as $class => $fieldsets ) {
				$column = new Child( 'div' );
				$column->setAttribute( 'class', $class );
				foreach ( $fieldsets as $i ) {
					if ( isset( $this->fieldsets[ $i ] ) ) {
						$fieldset = $this->fieldsets[ $i ];
						$fieldset->prepare();
						$column->addChild( $fieldset );
					}
				}
				$this->addChild( $column );
			}
		} else {
			foreach ( $this->fieldsets as $fieldset ) {
				$fieldset->prepare();
				$this->addChild( $fieldset );
			}
		}
		return $this;
	}

	/**
	 * Prepare the form for display through custom view.
	 *
	 * @return mixed
	 */
	public function prepareForView() {
		$formData = [];
		foreach ( $this->fieldsets as $fieldset ) {
			$formData = array_merge( $formData, $fieldset->prepareForView() );
		}
		return $formData;
	}

	/**
	 * Render the form.
	 *
	 * @param integer $depth
	 * @param [type]  $indent
	 * @param boolean $inner
	 * @return void
	 */
	public function render( $depth = 0, $indent = null, $inner = false ) {
		if ( ! ( $this->hasChildren() ) ) {
			$this->prepare();
		}
		foreach ( $this->fieldsets as $fieldset ) {
			foreach ( $fieldset->getAllFields() as $field ) {
				if ( $field instanceof Element\Input\File ) {
					$this->setAttribute( 'enctype', 'multipart/form-data' );
					break;
				}
			}
		}
		return parent::render( $depth, $indent, $inner );
	}

	/**
	 * Retrieve the markup to a string.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}

	/**
	 * Helper function to set a property.
	 *
	 * @param string $name field name.
	 * @param mixed  $value value.
	 */
	public function __set( $name, $value ) {
		$this->setFieldValue( $name, $value );
	}

	/**
	 * Helper function to get a value.
	 *
	 * @param string $name field name.
	 * @return mixed
	 */
	public function __get( $name ) {
		return $this->getFieldValue( $name );
	}

	/**
	 * Determine a property is set.
	 *
	 * @param string $name the field name.
	 * @return boolean
	 */
	public function __isset( $name ) {
		$fieldValues = $this->toArray();
		return isset( $fieldValues[ $name ] );
	}

	/**
	 * Remove a property from the field.
	 *
	 * @param string $name field name.
	 */
	public function __unset( $name ) {
		$fieldValues = $this->toArray();
		if ( isset( $fieldValues[ $name ] ) ) {
			$this->getField( $name )->resetValue();
		}
	}

	public function offsetExists( $offset ) {
		return $this->__isset( $offset );
	}

	public function offsetGet( $offset ) {
		return $this->__get( $offset );
	}

	public function offsetSet( $offset, $value ) {
		$this->__set( $offset, $value );
	}

	public function offsetUnset( $offset ) {
		$this->__unset( $offset );
	}

}
