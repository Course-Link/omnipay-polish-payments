<?php

namespace Omnipay\Paynow\Messages;

use CourseLink\Omnipay\HasLanguage;
use CourseLink\Omnipay\LanguageInterface;
use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\Paynow\HasPaynowCredentials;

abstract class AbstractRequest extends BaseRequest implements LanguageInterface
{
    use HasPaynowCredentials;
    use HasLanguage;

    protected string $endpoint = 'https://api.paynow.pl/';
    protected string $sandboxEndpoint = 'https://api.sandbox.paynow.pl/';

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->sandboxEndpoint : $this->endpoint;
    }

    public function getSupportedLanguages(): array
    {
        return ['pl', 'en', 'uk'];
    }
}