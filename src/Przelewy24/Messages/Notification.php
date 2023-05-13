<?php

namespace Omnipay\Przelewy24\Messages;

use CourseLink\Omnipay\ExtendedNotificationInterface;
use CourseLink\Omnipay\TransactionStatus;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Przelewy24\Gateway;

class Notification implements NotificationInterface, ExtendedNotificationInterface
{
    public function __construct(
        protected Gateway $gateway,
        protected array   $data,
    )
    {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getTransactionReference(): string
    {
        return $this->data['orderId'];
    }

    public function getTransactionExtendedStatus(): TransactionStatus
    {
        return $this->checkStatus() ? TransactionStatus::COMPLETED : TransactionStatus::PENDING;
    }

    public function getTransactionStatus(): string
    {
        return $this->getTransactionExtendedStatus()->toNotificationStatus();
    }

    public function getMessage(): string
    {
        return '';
    }

    protected function checkStatus(): bool
    {
        if (!$this->checkSignature()) {
            return false;
        }

        if (!$this->checkIpAddress()) {
            return false;
        }

        return true;
    }

    protected function checkSignature(): bool
    {
        $signature = $this->gateway->getSignature([
            'merchantId' => $this->gateway->getMerchantId(),
            'posId' => $this->gateway->getPosId(),
            'sessionId' => $this->data['sessionId'],
            'amount' => $this->data['amount'],
            'originAmount' => $this->data['originAmount'],
            'currency' => $this->data['currency'],
            'orderId' => $this->data['orderId'],
            'methodId' => $this->data['methodId'],
            'statement' => $this->data['statement'],
            'crc' => $this->gateway->getCrcKey(),
        ]);

        return $signature === $this->data['sign'];
    }

    protected function checkIpAddress(): bool
    {
        if (!$this->gateway->getVerifyIpAddress()) {
            return true;
        }

        if (!isset($this->data['ip_address'])) {
            return false;
        }

        if (!in_array($this->data['ip_address'], $this->gateway->getNotificationIpAddresses())) {
            return false;
        }

        return true;
    }
}