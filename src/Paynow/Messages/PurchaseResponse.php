<?php

namespace Omnipay\Paynow\Messages;

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
        return isset($this->data['redirectUrl']);
    }

    public function getTransactionReference()
    {
        return $this->data['paymentId'];
    }

    public function getRedirectUrl()
    {
        return $this->data['redirectUrl'];
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function getRedirectData(): ?array
    {
        return null;
    }
}