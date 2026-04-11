<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate\Core\Traits;

use ThemePlate\Core\Fields;

trait HasFields {

	protected ?Fields $fields = null;


	/**
	 * @param array<Field|mixed> $collection
	 */
	public function fields( array $collection ): self {

		$this->fields = new Fields( $collection );

		return $this;

	}

}
