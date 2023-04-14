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

        if ($this->data['order']['status'] !== 'COMPLETED') {
            return false;
        }

        return true;
    }

    protected function verifySignature(): bool
    {
        if(!isset($this->headers['openpayu-signature'][0])){
            return false;
        }

        $headers = array_reduce(
            explode(';', $this->headers['openpayu-signature'][0]),
            function ($carry, $item) {
                [$key, $value] = explode('=', $item);
                $carry[trim($key)] = trim($value);
                return $carry;
            }
        );

        if (!isset($headers['signature'])) {
            return false;
        }

        $incomingSignature = $headers['signature'];
        $expectedSignature = md5(json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . $this->gateway->getSignatureKey());

        return $incomingSignature === $expectedSignature;
    }
}