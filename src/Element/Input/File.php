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
use PNO\Dom\Child;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * File field class.
 */
class File extends Element\Input {

	/**
	 * Max file size allowed.
	 *
	 * @var string
	 */
	public $maxSize = null;

	/**
	 * Allowed file mime types.
	 *
	 * @var array
	 */
	public $mimeTypes = [];

	/**
	 * Constructor
	 *
	 * Instantiate the file input form element
	 *
	 * @param  string $name
	 * @param  string $value
	 * @param  string $indent
	 */
	public function __construct( $name, $value = null, $indent = null ) {
		parent::__construct( $name, 'file', $value, $indent );
	}

	/**
	 * Get the value of the form input element object.
	 *
	 * @return string
	 */
	public function getValue() {
		return maybe_unserialize( $this->getAttribute( 'value' ) );
	}

	/**
	 * Set a max size to the field.
	 *
	 * @param string $size size in bytes.
	 * @return void
	 */
	public function setMaxSize( $size ) {
		$this->maxSize = $size;
	}

	/**
	 * Retrieve max size assigned to the field.
	 *
	 * @return string
	 */
	public function getMaxSize() {
		return absint( $this->maxSize );
	}

	/**
	 * Set supported mime types.
	 *
	 * @param array $types list of types supported.
	 * @return void
	 */
	public function setMimeTypes( $types ) {
		$this->mimeTypes = $types;
	}

	/**
	 * Retrieve the list of allowed mime types.
	 *
	 * @return array
	 */
	public function getMimeTypes() {
		return (array) $this->mimeTypes;
	}

	/**
	 * Get name of a file field.
	 *
	 * @return string
	 */
	public function getName() {

		if ( $this->isMultiple() ) {
			return rtrim( $this->name, '[]' );
		}

		return $this->name;
	}

	/**
	 * Validate the form element object
	 *
	 * @return boolean
	 */
	public function validate() {

		$file_urls       = [];
		$files_to_upload = [];

		if ( isset( $_FILES[ $this->getName() ] ) && ! empty( $_FILES[ $this->getName() ] ) && ! empty( $_FILES[ $this->getName() ]['name'] ) ) {
			$files_to_upload = pno_prepare_uploaded_files( $_FILES[ $this->getName() ] );
		}

		// Check if the element is required
		if ( ( $this->required ) && empty( $files_to_upload ) ) {
			$this->errors[] = sprintf( esc_html__( '%s is a required field.', 'posterno' ), $this->getLabel() );
		}

		if ( ! empty( $files_to_upload ) && is_array( $files_to_upload ) ) {
			foreach ( $files_to_upload as $file_to_upload ) {

				$name = $file_to_upload['name'];
				$type = $file_to_upload['type'];
				$size = $file_to_upload['size'];

				if ( count( $this->validators ) > 0 ) {
					foreach ( $this->validators as $validator ) {
						if ( $validator instanceof \PNO\Validator\ValidatorInterface ) {

							$class = get_class( $validator );

							$supportedTypeValidators = [
								'PNO\Validator\ValueContained',
								'PNO\Validator\KeyContained',
							];

							$supportedSizeValidators = [
								'PNO\Validator\LessThanEqual',
								'PNO\Validator\GreaterThanEqual',
								'PNO\Validator\LessThan',
								'PNO\Validator\GreaterThan',
							];

							if ( in_array( $class, $supportedSizeValidators, true ) ) {
								if ( ! $validator->evaluate( $size ) ) {
									$this->errors[] = $validator->getMessage();
								}
							} elseif ( in_array( $class, $supportedTypeValidators, true ) ) {
								if ( ! $validator->evaluate( $type ) ) {
									$this->errors[] = $validator->getMessage();
								}
							} else {
								if ( ! $validator->evaluate( $name ) ) {
									$this->errors[] = $validator->getMessage();
								}
							}
						} elseif ( is_callable( $validator ) ) {
							$result = call_user_func_array( $validator, [ $name, $type, $size ] );
							if ( null !== $result ) {
								$this->errors[] = $result;
							}
						}
					}
				}
			}

			if ( count( $this->errors ) === 0 ) {
				foreach ( $files_to_upload as $file ) {

					$uploaded_file = pno_upload_file(
						$file,
						array(
							'file_key'           => $this->getName(),
							'allowed_mime_types' => $this->getMimeTypes(),
							'max_size'           => $this->getMaxSize() ? $this->getMaxSize() : wp_max_upload_size(),
						)
					);

					if ( is_wp_error( $uploaded_file ) ) {
						$this->errors[] = $uploaded_file->get_error_message();
					} else {
						$file_urls[] = [
							'url'  => esc_url( $uploaded_file->url ),
							'path' => wp_strip_all_tags( $uploaded_file->file ),
						];
					}
				}

				if ( $this->isMultiple() ) {
					$this->setValue( $file_urls );
				} else {
					$this->setValue( current( $file_urls ) );
				}
			}
		}

		return ( count( $this->errors ) == 0 );
	}
}
