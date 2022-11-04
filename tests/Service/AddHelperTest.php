<?php

namespace App\Tests\Service;

use App\Service\AddHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddHelperTest extends KernelTestCase
{
	public function testAdd()
	{
		self::bootKernel();
		$container = static::getContainer();

		$addHelper = $container->get(AddHelper::class);

		$this->assertEquals(3, $addHelper->add(1, 2));

	}
}
