<?php

namespace Omnipay\PayU\Messages;

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
        return isset($this->data['redirectUri']);
    }

    public function getRedirectUrl()
    {
        return $this->data['redirectUri'];
    }

    public function getTransactionReference()
    {
        return $this->data['orderId'];
    }
}