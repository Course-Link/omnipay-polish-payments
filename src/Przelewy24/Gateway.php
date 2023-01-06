<?php

namespace Omnipay\Przelewy24;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Przelewy24\Messages\CompletePurchaseRequest;
use Omnipay\Przelewy24\Messages\Notification;
use Omnipay\Przelewy24\Messages\PurchaseRequest;

class Gateway extends AbstractGateway
{
    use HasPrzelewy24Credentials;

    public function getName(): string
    {
        return 'Przelewy24';
    }

    public function purchase(array $options = []): RequestInterface
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function acceptNotification(array $options = []): NotificationInterface
    {
        return new Notification($this, $options);
    }

    public function completePurchase(array $options = []): RequestInterface
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }
}