<?php
//info The Serializer component has a built-in FlattenException normalizer (ProblemNormalizer) and
// JSON/XML/CSV/YAML encoders. When your application throws an exception, Symfony can output it in one
// of those formats. If you want to change the output contents, create a new Normalizer that supports the
// FlattenException input:
// wymaga -=> composer require symfony/serializer-pack

namespace App\Serializer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MyCustomProblemNormalizer implements NormalizerInterface
{
	public function normalize($exception, string $format = null, array $context = []): array
	{
		return [
			'content' => 'This is my custom problem normalizer.',
			'exception'=> [
				'message' => $exception->getMessage(),
				'code' => $exception->getStatusCode(),
			],
		];
	}

	public function supportsNormalization($data, string $format = null, array $context = []): bool
	{
		return $data instanceof FlattenException;
	}
}