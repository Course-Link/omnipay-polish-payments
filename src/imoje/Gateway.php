<?php

namespace Omnipay\imoje;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\imoje\Messages\Notification;
use Omnipay\imoje\Messages\PurchaseRequest;

class Gateway extends AbstractGateway
{
    use HasImojeCredentials;

    public function getName(): string
    {
        return 'imoje';
    }

    public function purchase(array $options = []): RequestInterface
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function acceptNotification(array $options = [], array $headers = []): NotificationInterface
    {
        return new Notification($this, $options, $headers);
    }
}