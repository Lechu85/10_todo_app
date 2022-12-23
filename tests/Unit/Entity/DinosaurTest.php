<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
use App\Enum\HealthStatus;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{
	public function testCanGetAndSetData(): void
	{
		$dino = new Dinosaur(
			name: 'Big Eaty',
			genus: 'Tyranosaurus',
			length: 15,
			enclosure: 'Paddock A'
		);

		self::assertSame('Big Eaty', $dino->getName());
		self::assertSame('Tyranosaurus', $dino->getGenus());
		self::assertSame(15, $dino->getLength());
		self::assertSame('Paddock A', $dino->getEnclosure());

	}

	/**
	 * @dataProvider sizeDescriptionProvider
	 *
	 * albo zapis jako atrybut #[dataProvider('sizeDescriptionProvider')]
	 */
	#
	public function testDinoHasCorrectSizeDescriptionFromLength(int $length, string $expectedSize): void
	{
		$dino = new Dinosaur(name: 'Diplodocus', length: $length);

		self::assertSame($expectedSize, $dino->getSizeDescription());
	}

	public function sizeDescriptionProvider(): \Generator
	{
		yield '10 meter Large dino' => [10, 'Large'];
		yield '5 meter Medium dino' => [5, 'Medium'];
		yield '3 meter Small dino' => [3, 'Small'];

	}

	public function testIsAcceptingVisitors(): void
	{
		$dino = new Dinosaur('Dinusek');

		self::assertTrue($dino->isAcceptingVisitors());
	}

	/**
	 * @dataProvider healthStatusProvider
	 */
	public function testIsNotAcceptingVisitorsOnHealthStatus(HealthStatus $healthStatus, bool $expectedVisitorsStatus): void
	{
		$dino = new Dinosaur('Bumpy');
		$dino->setHealth($healthStatus);

		//self::assertFalse($dino->isAcceptingVisitors());
		self::assertSame($expectedVisitorsStatus, $dino->isAcceptingVisitors());
	}

	public function healthStatusProvider(): \Generator
	{
		yield 'Sick dino is not accepting Visitors' => [HealthStatus::SICK, false];
		yield 'Hungry dino is accepting Visitors' => [HealthStatus::HEALTHY, true];

	}
}