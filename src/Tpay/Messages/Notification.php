<?php

namespace Omnipay\Tpay\Messages;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Tpay\Gateway;

class Notification implements NotificationInterface
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
        return $this->data['tr_id'];
    }

    public function getTransactionStatus(): string
    {
        return $this->checkStatus() ? NotificationInterface::STATUS_COMPLETED : NotificationInterface::STATUS_FAILED;
    }

    public function getMessage(): string
    {
        return $this->data['tr_error'];
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
        if (!isset($this->data['md5sum'])) {
            return false;
        }

        if ($this->getMd5Sum() !== $this->data['md5sum']) {
            return false;
        }

        return true;
    }

    protected function getMd5Sum(): string
    {
        return md5(implode('', [
            $this->gateway->getMerchantId(),
            $this->data['tr_amount'],
            $this->getTransactionReference(),
            $this->data['tr_crc'],
            $this->gateway->getSecurityCode(),
        ]));
    }

    protected function checkIpAddress(): bool
    {
        return $this->gateway->checkNotificationIPAddress();
    }
}