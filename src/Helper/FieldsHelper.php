<?php

/**
 * Helper functions
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Core\Helper;

use ThemePlate\Core\Field;
use ThemePlate\Core\Fields;

class FieldsHelper {

	/**
	 * @return mixed
	 */
	public static function get_default( Field $field ) {

		$default = $field->get_config( 'default' );

		if ( 'group' === $field->get_config( 'type' ) ) {
			/**
			 * @var Fields $fields
			 */
			$fields = $field->get_config( 'fields' );

			foreach ( $fields->get_collection() as $sub_field ) {
				if ( isset( $default[ $sub_field->data_key() ] ) ) {
					continue;
				}

				if ( ! is_array( $default ) ) {
					$default = array();
				}

				$default[ $sub_field->data_key() ] = self::get_default( $sub_field );
			}
		}

		return $default;

	}

}
