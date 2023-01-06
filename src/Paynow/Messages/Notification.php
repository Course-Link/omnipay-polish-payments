<?php

namespace Omnipay\Paynow\Messages;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Paynow\Gateway;

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
        return $this->data['paymentId'];
    }

    public function getTransactionStatus(): string
    {
        return $this->checkStatus() ? NotificationInterface::STATUS_COMPLETED : NotificationInterface::STATUS_FAILED;
    }

    public function getMessage()
    {
        return $this->data['status'];
    }

    protected function checkStatus(): bool
    {
        if (!isset($this->headers['signature'][0])) {
            return false;
        }

        return $this->gateway->calculateSignature($this->getData()) === $this->headers['signature'][0];
    }
}