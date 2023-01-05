<?php

namespace Omnipay\Tpay\Messages;

use CourseLink\Payments\HasLanguage;
use CourseLink\Payments\HasOAuth2Token;
use CourseLink\Payments\LanguageInterface;
use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\Tpay\HasTpayConfiguration;

abstract class AbstractRequest extends BaseRequest implements LanguageInterface
{
    use HasTpayConfiguration;
    use HasOAuth2Token;
    use HasLanguage;

    protected string $endpoint = 'https://api.tpay.com';
    protected string $sandboxEndpoint = 'https://api.tpay.com';

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->sandboxEndpoint : $this->endpoint;
    }

    public function getSupportedLanguages(): array
    {
        return ['pl', 'en', 'fr', 'es', 'it', 'ru'];
    }
}