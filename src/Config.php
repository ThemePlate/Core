<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate\Core;

class Config {

	protected string $prefix;
	protected ?Fields $fields;


	public function __construct( string $prefix, ?Fields $fields ) {

		$this->prefix = $prefix;
		$this->fields = $this->process( $fields );

	}


	protected function process( ?Fields $fields ): Fields {

		$collection = array();

		if ( null !== $fields ) {
			foreach ( $fields->get_collection() as $field ) {
				$collection[ $field->data_key( $this->get_prefix() ) ] = $field;
			}
		}

		return new Fields( $collection );

	}


	public function get_prefix(): string {

		return $this->prefix;

	}


	public function get_fields(): ?Fields {

		return $this->fields;

	}

}
