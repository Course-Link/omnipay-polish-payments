<?php

namespace Omnipay\Tpay\Messages;

use CourseLink\Omnipay\HasLanguage;
use CourseLink\Omnipay\HasOAuth2Token;
use CourseLink\Omnipay\LanguageInterface;
use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\Tpay\HasTpayConfiguration;

abstract class AbstractRequest extends BaseRequest implements LanguageInterface
{
    use HasTpayConfiguration;
    use HasOAuth2Token;
    use HasLanguage;

    protected string $endpoint = 'https://api.tpay.com';

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getSupportedLanguages(): array
    {
        return ['pl', 'en', 'fr', 'es', 'it', 'ru'];
    }
}