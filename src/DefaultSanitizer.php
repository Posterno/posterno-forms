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

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Default sanitization methods for most forms.
 */
trait DefaultSanitizer {

	/**
	 * Setup sanitization methods for the form.
	 *
	 * @param Form $form the form to sanizite.
	 * @return void
	 */
	public function addSanitizer( $form ) {

		$form->addFilter( new Filter\Filter( 'stripslashes', null, [ 'file' ] ) );

		$form->addFilter( $this->getInputSanitizer() );
		$form->addFilter( $this->getEditorSanitizer() );

	}

	/**
	 * Sanitization method for editor and textarea.
	 *
	 * @return Filter\Filter
	 */
	private function getEditorSanitizer() {

		$unsupportedTypes = pno_get_registered_field_types();

		$allowed = [
			'editor',
			'textarea',
		];

		foreach ( $unsupportedTypes as $type => $label ) {
			if ( in_array( $type, $allowed ) ) {
				unset( $unsupportedTypes[ $type ] );
			}
		}

		$unsupportedTypes = array_keys( $unsupportedTypes );

		$sanitizer = new Filter\Filter( 'wp_kses_post' );
		$sanitizer->setExcludeByType( $unsupportedTypes );

		return $sanitizer;

	}

	/**
	 * Sanitization method for all other fields.
	 *
	 * @return Filter\Filter
	 */
	private function getInputSanitizer() {

		$unsupportedTypes = [
			'textarea',
			'editor',
		];

		$sanitizer = new Filter\Filter( 'sanitize_text_field' );
		$sanitizer->setExcludeByType( $unsupportedTypes );

		return $sanitizer;

	}

}
