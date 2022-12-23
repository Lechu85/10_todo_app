<?php

namespace App\Tests\Unit\Service;

use App\Enum\HealthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GithubServiceTest extends TestCase
{
	private LoggerInterface $mockLogger;
	private MockHttpClient $mockHttpClient;
	private MockResponse $mockResponse;

	public function setUp(): void
	{
		$this->mockLogger = $this->createMock(LoggerInterface::class);
		$this->mockHttpClient = new MockHttpClient();
	}
	
	/**
	 * @dataProvider dinoNameProvider
	 */
	public function testGetHealthReportReturnsCorrectHealthStatusForDino(HealthStatus $expectedStatus, string $dinoName)
	{
		$service = $this->createGithubService([
			[
				'title' => 'Daisy',
				'labels' => [['name' => 'Status: Sick']]
			],
			[
				'title' => 'Maverick',
				'labels' => [['name' => 'Status: Healthy']]
			]
		]);

		self::assertSame($expectedStatus, $service->getHealthReport($dinoName));
		self::assertSame(1, $this->mockHttpClient->getRequestsCount());
		self::assertSame('GET', $this->mockResponse->getRequestMethod());
		self::assertSame(
			'https://api.github.com/repos/SymfonyCasts/dino-park/issues',
			$this->mockResponse->getRequestUrl()
		);
	}

	/**
	 * @dataProvider dinoNameProvider
	 */
	public function testGetHealthReportReturnsCorrectHealthStatusForDinoOtherWay(HealthStatus $expectedStatus, string $dinoName)
	{
		$mockLogger = $this->createMock( LoggerInterface::class);
		$mockHttpClient = $this->createMock(HttpClientInterface::class);
		$mockResponse = $this->createMock(ResponseInterface::class);

		//info Następnie trzeba nauczyć naszego zmockowanego klienta, co ma robić, jeżeli wywołamy metode ->toArray(). Ma zwracać tablice z danymi, ponieważ to robi normalne API.
		$mockResponse
			->method('toArray')
			->willReturn([
				[
					'title' => 'Daisy',
					'labels' => [['name' => 'Status: Sick']]
				],
				[
					'title' => 'Maverick',
					'labels' => [['name' => 'Status: Healthy']]
				]
			])
		;

		//info Za każdym razem kiedy za każdym razem kiedy wykonujemy metode request na Mocku, Ty musisz zwrócić ten mock Response.
		$mockHttpClient
			->expects(self::once()) //wykonuj tylko raz.
			->method('request')
			->with('GET', 'https://api.github.com/repos/SymfonyCasts/dino-park/issues')
			->willReturn($mockResponse)
		;

		$service = new GithubService( $mockHttpClient, $mockLogger);

		self::assertSame($expectedStatus, $service->getHealthReport($dinoName));
	}

	/**
	 * @dataProvider dinoNameProvider
	 */
	public function testExceptionThrownWithUnknownLabel()
	{
		$mockLogger = $this->createMock( LoggerInterface::class);
		$mockHttpClient = $this->createMock(HttpClientInterface::class);
		$mockResponse = $this->createMock(ResponseInterface::class);

		//info Następnie trzeba nauczyć naszego zmockowanego klienta, co ma robić, jeżeli wywołamy metode ->toArray(). Ma zwracać tablice z danymi, ponieważ to robi normalne API.
		$mockResponse
			->method('toArray')
			->willReturn([
				[
					'title' => 'Maverick',
					'labels' => [['name' => 'Status: Drowsy']]
				]
			])
		;

		//info Za każdym razem kiedy za każdym razem kiedy wykonujemy metode request na Mocku, Ty musisz zwrócić ten mock Response.
		$mockHttpClient
			->expects(self::once()) //wykonuj tylko raz.
			->method('request')
			->with('GET', 'https://api.github.com/repos/SymfonyCasts/dino-park/issues')
			->willReturn($mockResponse)
		;

		$service = new GithubService( $mockHttpClient, $mockLogger);

		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Drowsy is an unknown status label');

		$service->getHealthReport('Maverick');
	}

	/**
	 * Identyczny test jak wyżej, ale użyliśmy mocku i zaopszczędzilismy ogrom lini kodu.
	 * @dataProvider dinoNameProvider
	 */
	public function testExceptionThrownWithUnknownLabelWithMock()
	{

		$service = $this->createGithubService([
			[
				'title' => 'Maverick',
				'labels' => [['name' => 'Status: Drowsy']]
			]
		]);

		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Drowsy is an unknown status label');

		$service->getHealthReport('Maverick');
	}
	public function dinoNameProvider(): \Generator
	{
		yield 'Sick Dino' => [
			HealthStatus::SICK,
			'Daisy',
		];

		yield 'Healthy Dino' => [
			HealthStatus::HEALTHY,
			'Maverick'
		];
	}

	public function createGithubService(array $responseData): GithubService
	{
		$this->mockResponse = new MockResponse(json_encode($responseData));

		$this->mockHttpClient->setResponseFactory($this->mockResponse);

		return new GithubService($this->mockHttpClient, $this->mockLogger);
	}

}