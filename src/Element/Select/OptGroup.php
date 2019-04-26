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
namespace PNO\Form\Element\Select;

use PNO\Dom\Child;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Dropdown optgroup tag field class.
 */
class Optgroup extends Child {

	/**
	 * Constructor
	 *
	 * Instantiate the option element object
	 *
	 * @param  string $value
	 * @param  array  $options
	 */
	public function __construct( $value = null, array $options = [] ) {
		parent::__construct( 'optgroup', $value, $options );
	}

	/**
	 * Add an option element
	 *
	 * @param  Child $option
	 * @return Optgroup
	 */
	public function addOption( Child $option ) {
		$this->addChild( $option );
		return $this;
	}

	/**
	 * Add option elements
	 *
	 * @param  array $options
	 * @return Optgroup
	 */
	public function addOptions( array $options ) {
		$this->addChildren( $options );
		return $this;
	}

	/**
	 * Get option elements
	 *
	 * @return array
	 */
	public function getOptions() {
		$options = [];

		foreach ( $this->childNodes as $child ) {
			if ( $child instanceof Option ) {
				$options[] = $child;
			}
		}

		return $options;
	}

}
