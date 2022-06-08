<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate\Core;

class Repository {

	/**
	 * @var Fields[]
	 */
	protected array $items = array();


	public function store( Config $config ): void {

		$this->items[] = $config->get_fields();

	}


	public function retrieve( string $key ): Field {

		foreach ( $this->items as $fields ) {
			foreach ( $fields->get_collection() as $field_key => $field ) {
				if ( $key === $field_key ) {
					return $field;
				}
			}
		}

		return $this->field( $key );

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
