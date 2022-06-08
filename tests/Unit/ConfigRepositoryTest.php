<?php

/**
 * @package ThemePlate
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ThemePlate\Core\Config;
use ThemePlate\Core\Field;
use ThemePlate\Core\Fields;
use ThemePlate\Core\Repository;

class ConfigRepositoryTest extends TestCase {
	private Repository $repository;

	public function setUp(): void {
		$this->repository = new Repository();
	}

	public function test_repository(): void {
		$this->assertInstanceOf( Field::class, $this->repository->retrieve( 'test' ) );
		$this->assertIsArray( $this->repository->dump() );
	}

	public function test_with_config(): void {
		$config = new Config( '', null );

		$this->repository->store( $config );
		$this->test_repository();
	}

	public function test_with_fields(): void {
		$fields = new Fields(
			array(
				'test' => array(
					'type' => 'type',
				),
				'this' => array(
					'type' => 'date',
				),
			)
		);
		$config = new Config( 'prefix_', $fields );

		$this->repository->store( $config );
		$this->assertInstanceOf( Field\TypeField::class, $this->repository->retrieve( 'prefix_test' ) );
		$this->assertInstanceOf( Field\DateField::class, $this->repository->retrieve( 'prefix_this' ) );
		$this->assertInstanceOf( Field::class, $this->repository->retrieve( 'prefix_unknown' ) );
	}
}
