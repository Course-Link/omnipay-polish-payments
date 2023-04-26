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
        $this->validate();

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

    /**
     * @throws InvalidResponseException
     */
    protected function validate(): bool
    {
        if (!isset($this->data['paymentId'])) {
            throw new InvalidResponseException('Missing paymentId');
        }

        if (!isset($this->data['status'])) {
            throw new InvalidResponseException('Missing status');
        }

        if (!isset($this->headers['signature'][0])) {
            throw new InvalidResponseException('Missing signature header');
        }

        if ($this->gateway->calculateSignature($this->getData()) !== $this->headers['signature'][0]) {
            throw new InvalidResponseException('Invalid signature');
        }

        return true;
    }
}