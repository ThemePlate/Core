<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate\Core;

class Repository {

	protected array $items = array();


	public function store( Config $config ): void {

		foreach ( $config->get_types() as $type ) {
			$this->items[ $type ] = $config->get_fields();
		}

	}


	public function retrieve( string $type, string $key ): Field {

		if ( empty( $this->items[ $type ] ) ) {
			return $this->field( $key );
		}

		$fields = $this->items[ $type ]->get_collection();

		return $fields[ $key ] ?? $this->field( $key );

	}


	protected function field( $key ): Field {

		return new class( $key ) extends Field {
			public function render( $value ): void {}
		};

	}


	public function dump(): array {

		return $this->items;

	}

}
