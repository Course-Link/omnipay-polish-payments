<?php

namespace CourseLink\Omnipay\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Omnipay\Common\Http\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class TestCase extends PHPUnitTestCase
{
    use MockeryPHPUnitIntegration;

    private MockHandler $mockHandler;
    private HandlerStack $handlerStack;
    private GuzzleClient $guzzleClient;
    private GuzzleAdapter $guzzleAdapter;
    private ClientInterface $httpClient;


    protected function setUp(): void
    {
        parent::setUp();
        $this->mockHandler = new MockHandler();
        $this->handlerStack = HandlerStack::create($this->mockHandler);
        $this->guzzleClient = new GuzzleClient([
            'handler' => $this->handlerStack,
            'allow_redirects' => false,
        ]);
        $this->httpRequest = new HttpRequest;
        $this->guzzleAdapter = new GuzzleAdapter($this->guzzleClient);
        $this->httpClient = new Client($this->guzzleAdapter);
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    public function getHttpRequest(): HttpRequest
    {
        return $this->httpRequest;
    }

    public function mockResponse(Response $response)
    {
        $this->mockHandler->append($response);
    }
}