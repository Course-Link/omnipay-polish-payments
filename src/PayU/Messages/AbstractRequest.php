<?php

namespace Omnipay\PayU\Messages;

use CourseLink\Payments\HasLanguage;
use CourseLink\Payments\HasOAuth2Token;
use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\PayU\HasPayUCredentials;

abstract class AbstractRequest extends BaseRequest
{
    use HasPayUCredentials;
    use HasOAuth2Token;
    use HasLanguage;

    protected string $endpoint = 'https://secure.payu.com';
    protected string $sandboxEndpoint = 'https://secure.snd.payu.com';

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->sandboxEndpoint : $this->endpoint;
    }

    public function getSupportedLanguages(): array
    {
        return ['pl', 'en', 'cs', 'de', 'es', 'it', 'nl', 'sk'];
    }
}