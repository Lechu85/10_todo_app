<?php

namespace App\Service;

class AddHelper2Decorator
{

	private $decorated;

	public function __construct(AddHelper2 $decorated)
	{
		$this->decorated = $decorated;
	}

	public function add(int $a, int $b): int
	{

		$result = $this->decorated->add($a, $b);

		return $result + 1;
	}

}