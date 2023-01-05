<?php

namespace Omnipay\PayU\Messages;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\PayU\Gateway;

class Notification implements NotificationInterface
{
    public function __construct(
        protected Gateway $gateway,
        protected array   $data,
        protected array   $headers,
    )
    {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getTransactionReference(): string
    {
        return $this->data['order']['orderId'];
    }

    public function getTransactionStatus(): string
    {
        return $this->checkStatus() ? NotificationInterface::STATUS_COMPLETED : NotificationInterface::STATUS_FAILED;
    }

    public function getMessage(): string
    {
        return '';
    }

    protected function checkStatus(): bool
    {
        if (!$this->verifySignature()) {
            return false;
        }

        return true;
    }

    protected function verifySignature(): bool
    {
        if (!isset($this->headers['OpenPayu-Signature']['signature'])) {
            return false;
        }

        $signature = $this->headers['OpenPayu-Signature']['signature'];

        $expectedSignature = md5(
            json_encode($this->data) . $this->gateway->getSignatureKey()
        );

        return $signature === $expectedSignature;
    }
}