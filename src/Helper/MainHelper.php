<?php

/**
 * Helper functions
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Core\Helper;

class MainHelper {

	public static function fool_proof( array $defaults, array $options ): array {

		$result = array_merge( $defaults, $options );

		foreach ( $defaults as $key => $value ) {
			if ( is_array( $value ) ) {
				$result[ $key ] = (array) $result[ $key ];
			}

			if ( is_bool( $value ) ) {
				$result[ $key ] = (bool) $result[ $key ];
			}

			if ( is_int( $value ) ) {
				if ( is_scalar( $result[ $key ] ) || null === $result[ $key ] ) {
					$result[ $key ] = (int) $result[ $key ];
				} else {
					$result[ $key ] = (int) ( (bool) $result[ $key ] );
				}
			}

			if ( is_string( $value ) ) {
				if ( is_scalar( $result[ $key ] ) || null === $result[ $key ] ) {
					$result[ $key ] = (string) $result[ $key ];
				} else {
					// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
					$result[ $key ] = json_encode( $result[ $key ] );
				}
			}
		}

		return $result;

	}


	public static function is_sequential( array $array ): bool {

		return ( array_keys( $array ) === range( 0, count( $array ) - 1 ) );

	}


	public static function is_complete( array $config, array $expected ): bool {

		$result = true;

		foreach ( $expected as $key ) {
			if ( is_array( $key ) ) {
				$result = false;

				foreach ( $key as $k ) {
					if ( array_key_exists( $k, $config ) ) {
						$result = true;
					}
				}
			} elseif ( ! array_key_exists( $key, $config ) ) {
				return false;
			}
		}

		return $result;

	}


	public static function values_to_string( array $array ): array {

		return array_map(
			function( $value ) {
				if ( is_array( $value ) ) {
					return self::values_to_string( $value );
				}

				return (string) $value;
			},
			$array
		);

	}


	/**
	 * @param $value array|string|null
	 */
	public static function for_repeatable( $value ): bool {

		return is_array( $value ) && self::is_sequential( $value ) && is_array( $value[0] );

	}

}
