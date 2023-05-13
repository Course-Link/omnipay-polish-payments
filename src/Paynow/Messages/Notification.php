<?php

namespace Omnipay\Paynow\Messages;

use CourseLink\Omnipay\ExtendedNotificationInterface;
use CourseLink\Omnipay\TransactionStatus;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Paynow\Gateway;

class Notification implements NotificationInterface, ExtendedNotificationInterface
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
    public function getTransactionExtendedStatus(): TransactionStatus
    {
        $this->validate();

        return match ($this->data['status']) {
            'NEW', 'PENDING' => TransactionStatus::PENDING,
            'CONFIRMED' => TransactionStatus::COMPLETED,
            'EXPIRED', 'ABANDONED' => TransactionStatus::CANCELED,
            'ERROR' => TransactionStatus::ERROR,
        };
    }

    /**
     * @throws InvalidResponseException
     */
    public function getTransactionStatus(): string
    {
        return $this->getTransactionExtendedStatus()->toNotificationStatus();
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