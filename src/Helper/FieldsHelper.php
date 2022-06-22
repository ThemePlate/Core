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

	public static function get_type( Field $field ): string {

		switch ( $field->get_config( 'type' ) ) {
			default:
				return 'string';

			case 'group':
				return 'object';
		}

	}

	/**
	 * @return mixed
	 */
	public static function get_default( Field $field ) {

		$default = $field->get_config( 'default' );

		if ( 'group' === $field->get_config( 'type' ) ) {
			if ( empty( $field->get_config( 'fields' ) ) ) {
				return $default;
			}

			$fields = self::group_fields( $field->get_config( 'fields' ) );

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


	/**
	 * @param array|Fields $group_fields
	 */
	public static function group_fields( $group_fields ): Fields {

		return $group_fields instanceof Fields ? $group_fields : new Fields( (array) $group_fields );

	}

}
