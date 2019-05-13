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
 * Textarea field class.
 */
class Editor extends Textarea {

	/**
	 * Initialize the editor.
	 *
	 * @param string $name field configuration.
	 * @param string $value field configuration.
	 * @param string $indent field configuration.
	 */
	public function __construct( $name, $value = null, $indent = null ) {
		parent::__construct( $name, $value = null, $indent = null );
	}

	/**
	 * Get form element object type
	 *
	 * @return string
	 */
	public function getType() {
		return 'editor';
	}

	/**
	 * Render the field.
	 *
	 * @return string
	 */
	public function render( $depth = 0, $indent = NULL, $inner = false ) {

		\ob_start();

		$editor = apply_filters(
			'pno_wp_editor_args',
			array(
				'textarea_name' => esc_attr( $this->getName() ),
				'media_buttons' => false,
				'textarea_rows' => 8,
				'quicktags'     => false,
				'tinymce'       => array(
					'plugins'                       => 'lists,paste,tabfocus,wplink,wordpress',
					'paste_as_text'                 => true,
					'paste_auto_cleanup_on_paste'   => true,
					'paste_remove_spans'            => true,
					'paste_remove_styles'           => true,
					'paste_remove_styles_if_webkit' => true,
					'paste_strip_class_attributes'  => true,
					'toolbar1'                      => 'bold,italic,|,bullist,numlist,|,link,unlink,|,undo,redo',
					'toolbar2'                      => '',
					'toolbar3'                      => '',
					'toolbar4'                      => '',
				),
			)
		);

		wp_editor( ! empty( $this->getValue() ) ? wp_kses_post( wp_specialchars_decode( $this->getValue() ) ) : '', 'pno-field-' . esc_attr( $this->getName() ), $editor );

		return ob_get_clean();

	}

}
