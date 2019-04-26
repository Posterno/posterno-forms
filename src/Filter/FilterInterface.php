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

interface FilterInterface {

	/**
	 * Set callable.
	 *
	 * @param  callable $callable function name.
	 * @return FilterInterface
	 */
	public function setCallable( callable $callable);

	/**
	 * Set params.
	 *
	 * @param  mixed $params parameters to send through.
	 * @return FilterInterface
	 */
	public function setParams( $params);

	/**
	 * Set exclude by type.
	 *
	 * @param  mixed $excludeByType field types to exclude.
	 * @return FilterInterface
	 */
	public function setExcludeByType( $excludeByType);

	/**
	 * Set exclude by name.
	 *
	 * @param  mixed $excludeByName fields by name to exclude.
	 * @return FilterInterface
	 */
	public function setExcludeByName( $excludeByName);

	/**
	 * Get callable.
	 *
	 * @return callable
	 */
	public function getCallable();

	/**
	 * Get params.
	 *
	 * @return array
	 */
	public function getParams();

	/**
	 * Get exclude by type.
	 *
	 * @return array
	 */
	public function getExcludeByType();

	/**
	 * Get exclude by name.
	 *
	 * @return array
	 */
	public function getExcludeByName();

	/**
	 * Has callable.
	 *
	 * @return boolean
	 */
	public function hasCallable();

	/**
	 * Has params.
	 *
	 * @return boolean
	 */
	public function hasParams();

	/**
	 * Has exclude by type.
	 *
	 * @return boolean
	 */
	public function hasExcludeByType();

	/**
	 * Has exclude by name.
	 *
	 * @return boolean
	 */
	public function hasExcludeByName();

	/**
	 * Filter value.
	 *
	 * @param  mixed  $value value to filter.
	 * @param  mixed  $type field type.
	 * @param  string $name field name.
	 * @return mixed
	 */
	public function filter( $value, $type = null, $name = null);
}
