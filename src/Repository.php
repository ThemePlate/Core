<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate\Core;

class Repository {

	protected array $items;


	public function store( Config $config ): void {

		foreach ( $config->get_fields()->get_collection() as $field ) {
			foreach ( $config->get_types() as $type ) {
				$key = $field->data_key( $config->get_prefix() );

				$this->items[ $type ][ $key ] = $field;
			}
		}

	}


	public function retrieve( string $type, string $key ): Field {

		if ( isset( $this->items[ $type ][ $key ] ) ) {
			return $this->items[ $type ][ $key ];
		}

		return new class( $type ) extends Field {
			public function render( $value ): void {}
		};

	}


	public function dump(): array {

		return $this->items;

	}

}
