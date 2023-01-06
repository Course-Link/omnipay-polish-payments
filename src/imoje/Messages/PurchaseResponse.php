<?php

namespace Omnipay\imoje\Messages;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful(): bool
    {
        return false;
    }

    public function isRedirect(): bool
    {
        return isset($this->data['payment']['url']);
    }

    public function getTransactionReference(): string
    {
        return $this->data['payment']['id'];
    }

    public function getRedirectUrl(): string
    {
        return $this->data['payment']['url'];
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}