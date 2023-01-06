<?php

namespace Omnipay\Przelewy24\Messages;

use CourseLink\Omnipay\HasLanguage;
use CourseLink\Omnipay\LanguageInterface;
use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\Przelewy24\HasPrzelewy24Credentials;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractRequest extends BaseRequest implements LanguageInterface
{
    use HasPrzelewy24Credentials;
    use HasLanguage;

    protected string $endpoint = 'https://secure.przelewy24.pl/';
    protected string $sandboxEndpoint = 'https://sandbox.przelewy24.pl/';

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->sandboxEndpoint : $this->endpoint;
    }

    public function getSupportedLanguages(): array
    {
        return ['pl', 'en', 'fr', 'es', 'it', 'ru'];
    }

    protected function sendRequest(string $method, string $endpoint, array $data): ResponseInterface
    {
        return $this->httpClient->request(
            $method,
            $this->getEndpoint() . $endpoint,
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(implode(':', [$this->getMerchantId(), $this->getReportKey()]))
            ],
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
}