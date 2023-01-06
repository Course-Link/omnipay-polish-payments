<?php

namespace Omnipay\imoje\Messages;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\imoje\Gateway;

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
        return $this->data['transaction']['id'];
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
        if (!isset($this->data['transaction']['status'])) {
            return false;
        }

        if ($this->data['transaction']['status'] !== 'settled') {
            return false;
        }

        if (!$this->checkSignature()) {
            return false;
        }

        return true;
    }

    protected function checkSignature(): bool
    {
        $merchantId = $this->headers['X-IMoje-Signature']['merchantid'];
        $serviceId = $this->headers['X-IMoje-Signature']['serviceid'];
        $signature = $this->headers['X-IMoje-Signature']['signature'];
        $alg = $this->headers['X-IMoje-Signature']['alg'];
        $body = $this->getData();

        $expectedSignature = hash($alg, json_encode($body) . $this->gateway->getServiceKey());

        return $signature === $expectedSignature;
    }
}