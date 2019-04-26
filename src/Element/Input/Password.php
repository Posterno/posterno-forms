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
namespace PNO\Form\Element\Input;

use PNO\Form\Element;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Password field class.
 */
class Password extends Element\Input {

	/**
	 * Flag to allow rendering the value
	 *
	 * @var boolean
	 */
	protected $renderValue = false;

	/**
	 * Constructor
	 *
	 * Instantiate the password input form element
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  string  $indent
	 * @param  boolean $renderValue
	 */
	public function __construct( $name, $value = null, $indent = null, $renderValue = false ) {
		parent::__construct( $name, 'password', $value, $indent );
	}

	/**
	 * Set the render value flag.
	 *
	 * @param  boolean $renderValue
	 * @return Password
	 */
	public function setRenderValue( $renderValue ) {
		$this->renderValue = (bool) $renderValue;
		return $this;
	}

	/**
	 * Get the render value flag.
	 *
	 * @return boolean
	 */
	public function getRenderValue() {
		return $this->renderValue;
	}

	/**
	 * Render the password element.
	 *
	 * @param  int     $depth
	 * @param  string  $indent
	 * @param  boolean $inner
	 * @return mixed
	 */
	public function render( $depth = 0, $indent = null, $inner = false ) {
		if ( ! $this->renderValue ) {
			$this->setAttribute( 'value', '' );
		}
		return parent::render( $depth, $indent, $inner );
	}

}
