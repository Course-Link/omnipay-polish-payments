<?php

namespace Omnipay\Przelewy24;

use CourseLink\Omnipay\HasNotificationIPVerification;
use CourseLink\Omnipay\NotificationIPVerificationInterface;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Przelewy24\Messages\CompletePurchaseRequest;
use Omnipay\Przelewy24\Messages\Notification;
use Omnipay\Przelewy24\Messages\PurchaseRequest;

class Gateway extends AbstractGateway implements NotificationIPVerificationInterface
{
    use HasPrzelewy24Credentials;
    use HasNotificationIPVerification;

    public function getName(): string
    {
        return 'Przelewy24';
    }

    public function getDefaultNotificationIpAddresses(): array
    {
        return [
            '91.216.191.181',
            '91.216.191.182',
            '91.216.191.183',
            '91.216.191.184',
            '91.216.191.185',
            '5.252.202.254',
            '5.252.202.255',
        ];
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