<?php

namespace Omnipay\imoje\Messages;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\imoje\Gateway;
use Symfony\Component\HttpFoundation\HeaderUtils;

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
        if (!isset($this->headers['x-imoje-signature'][0])) {
            return false;
        }

        $headers = array_reduce(
            explode(';', $this->headers['x-imoje-signature'][0]),
            function ($carry, $item) {
                [$key, $value] = explode('=', $item);
                $carry[trim($key)] = trim($value);
                return $carry;
            }
        );

        if (!isset($headers['signature'], $headers['merchantid'], $headers['serviceid'], $headers['alg'])) {
            return false;
        }

        $body = $this->getData();

        $expectedSignature = hash($headers['alg'], json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . $this->gateway->getServiceKey());

        return $headers['signature'] === $expectedSignature;
    }
}