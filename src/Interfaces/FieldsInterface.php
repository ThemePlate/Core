<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate\Core\Interfaces;

interface FieldsInterface {

	/**
	 * @param array<Field|mixed> $collection
	 */
	public function fields( array $collection ): self;

}
