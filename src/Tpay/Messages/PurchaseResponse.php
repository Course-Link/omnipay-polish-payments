<?php

namespace Omnipay\Tpay\Messages;

use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return false;
    }

    public function isRedirect(): bool
    {
        return $this->checkStatus();
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['title'];
    }

    public function getRedirectUrl(): string
    {
        return $this->data['transactionPaymentUrl'];
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    protected function checkStatus(): bool
    {
        return $this->data['result'] === 'success' && isset($this->data['transactionPaymentUrl'], $this->data['title']);
    }

    public function getMessage(): ?string
    {
        return $this->data['errors'][0]['errorMessage'] ?? null;
    }
}