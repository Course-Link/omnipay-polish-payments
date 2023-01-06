<?php

namespace Omnipay\imoje\Messages;

use CourseLink\Omnipay\HasLanguage;
use CourseLink\Omnipay\LanguageInterface;
use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\imoje\HasImojeCredentials;

abstract class AbstractRequest extends BaseRequest implements LanguageInterface
{
    use HasImojeCredentials;
    use HasLanguage;

    protected string $endpoint = 'https://api.imoje.pl/v1/merchant/';
    protected string $sandboxEndpoint = 'https://sandbox.api.imoje.pl/v1/merchant/';

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->sandboxEndpoint : $this->endpoint;
    }

    public function getSupportedLanguages(): array
    {
        return ['pl', 'en', 'cs', 'de', 'es', 'fr', 'it', 'lt', 'ru', 'sk', 'sl', 'uk'];
    }
}