<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate\Core;

class Config {

	protected string $prefix;
	protected array $types;
	protected ?Fields $fields;


	public function __construct( string $prefix, array $types, ?Fields $fields ) {

		$this->prefix = $prefix;
		$this->types  = $types;
		$this->fields = $fields;

	}


	public function get_prefix(): string {

		return $this->prefix;

	}


	public function get_types(): array {

		return $this->types;

	}


	public function get_fields(): ?Fields {

		return $this->fields;

	}

}
