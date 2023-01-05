<?php

namespace Omnipay\imoje\Messages;

use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\imoje\HasImojeCredentials;

abstract class AbstractRequest extends BaseRequest
{
    use HasImojeCredentials;

    protected string $endpoint = 'https://api.imoje.pl/v1/merchant/';
    protected string $sandboxEndpoint = 'https://sandbox.api.imoje.pl/v1/merchant/';

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->sandboxEndpoint : $this->endpoint;
    }
}