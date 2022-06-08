<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate\Core;

class Repository {

	protected array $items = array();


	public function store( Config $config ): void {

		$this->items += $config->get_fields();

	}


	public function retrieve( string $key ): Field {

		return $this->items[ $key ] ?? $this->field( $key );

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
