<?php

namespace Omnipay\Paynow\Messages;

use Omnipay\Common\Exception\InvalidResponseException;
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

    /**
     * @throws InvalidResponseException
     */
    public function getTransactionStatus(): string
    {
        if (!$this->validate()) {
            throw new InvalidResponseException();
        }

        return match ($this->data['status']) {
            'NEW', 'PENDING' => NotificationInterface::STATUS_PENDING,
            'CONFIRMED' => NotificationInterface::STATUS_COMPLETED,
            'EXPIRED', 'ERROR', 'ABANDONED' => NotificationInterface::STATUS_FAILED,
        };
    }

    public function getMessage()
    {
        return $this->data['status'];
    }

    protected function validate(): bool
    {
        if (!isset($this->data['paymentId'])) {
            return false;
        }

        if (!isset($this->data['status'])) {
            return false;
        }

        if (!isset($this->headers['signature'][0])) {
            return false;
        }

        if ($this->gateway->calculateSignature($this->getData()) !== $this->headers['signature'][0]) {
            return false;
        }

        return true;
    }
}