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
namespace PNO\Form\Filter;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Form field filter class.
 */
abstract class AbstractFilter implements FilterInterface {

	/**
	 * Filter callable.
	 *
	 * @var mixed
	 */
	protected $callable = null;

	/**
	 * Parameters.
	 *
	 * @var array
	 */
	protected $params = [];

	/**
	 * Exclude by type.
	 *
	 * @var array
	 */
	protected $excludeByType = [];

	/**
	 * Exclude by name.
	 *
	 * @var array
	 */
	protected $excludeByName = [];

	/**
	 * Get things started.
	 *
	 * @param  callable $callable function to call.
	 * @param  mixed    $params paramers to send to the function.
	 * @param  mixed    $excludeByType fields to exclude by type.
	 * @param  mixed    $excludeByName fields to exclude by name.
	 */
	public function __construct( callable $callable, $params = null, $excludeByType = null, $excludeByName = null ) {
		$this->setCallable( $callable );
		if ( null !== $params ) {
			$this->setParams( $params );
		}
		if ( null !== $excludeByType ) {
			$this->setExcludeByType( $excludeByType );
		}
		if ( null !== $excludeByName ) {
			$this->setExcludeByName( $excludeByName );
		}
	}

	/**
	 * Set callable function name.
	 *
	 * @param  callable $callable function name.
	 * @return AbstractFilter
	 */
	public function setCallable( callable $callable ) {
		$this->callable = $callable;
		return $this;
	}

	/**
	 * Set params to send to the function.
	 *
	 * @param  mixed $params list of parameters.
	 * @return AbstractFilter
	 */
	public function setParams( $params ) {
		if ( ! is_array( $params ) ) {
			$params = [ $params ];
		}
		$this->params = $params;
		return $this;
	}

	/**
	 * Set exclude by type.
	 *
	 * @param  mixed $excludeByType list of field types.
	 * @return AbstractFilter
	 */
	public function setExcludeByType( $excludeByType ) {
		if ( ! is_array( $excludeByType ) ) {
			$excludeByType = [ $excludeByType ];
		}
		$this->excludeByType = $excludeByType;
		return $this;
	}

	/**
	 * Set exclude by name.
	 *
	 * @param  mixed $excludeByName list of field names.
	 * @return AbstractFilter
	 */
	public function setExcludeByName( $excludeByName ) {
		if ( ! is_array( $excludeByName ) ) {
			$excludeByName = [ $excludeByName ];
		}
		$this->excludeByName = $excludeByName;
		return $this;
	}

	/**
	 * Get callable.
	 *
	 * @return callable
	 */
	public function getCallable() {
		return $this->callable;
	}

	/**
	 * Get params.
	 *
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * Get exclude by type.
	 *
	 * @return array
	 */
	public function getExcludeByType() {
		return $this->excludeByType;
	}

	/**
	 * Get exclude by name.
	 *
	 * @return array
	 */
	public function getExcludeByName() {
		return $this->excludeByName;
	}

	/**
	 * Has callable.
	 *
	 * @return boolean
	 */
	public function hasCallable() {
		return ( null !== $this->callable );
	}

	/**
	 * Has params.
	 *
	 * @return boolean
	 */
	public function hasParams() {
		return ( ! empty( $this->params ) );
	}

	/**
	 * Has exclude by type.
	 *
	 * @return boolean
	 */
	public function hasExcludeByType() {
		return ( ! empty( $this->excludeByType ) );
	}

	/**
	 * Has exclude by name.
	 *
	 * @return boolean
	 */
	public function hasExcludeByName() {
		return ( ! empty( $this->excludeByName ) );
	}

	/**
	 * Filter value.
	 *
	 * @param  mixed  $value value to filter.
	 * @param  mixed  $type field type.
	 * @param  string $name field name.
	 * @return mixed
	 */
	public function filter( $value, $type = null, $name = null ) {
		if ( ( ( null === $type ) || ( ! in_array( $type, $this->excludeByType ) ) ) &&
			( ( null === $name ) || ( ! in_array( $name, $this->excludeByName ) ) ) ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $k => $v ) {
					$params      = array_merge( [ $v ], $this->params );
					$value[ $k ] = call_user_func_array( $this->callable, $params );
				}
			} else {
				$params = array_merge( [ $value ], $this->params );
				$value  = call_user_func_array( $this->callable, $params );
			}
		}
		return $value;
	}
}
