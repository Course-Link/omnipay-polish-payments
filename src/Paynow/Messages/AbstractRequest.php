<?php

namespace Omnipay\Paynow\Messages;

use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\Paynow\HasPaynowCredentials;

abstract class AbstractRequest extends BaseRequest
{
    use HasPaynowCredentials;

    protected string $endpoint = 'https://api.paynow.pl/';
    protected string $sandboxEndpoint = 'https://api.sandbox.paynow.pl/';

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->sandboxEndpoint : $this->endpoint;
    }
}