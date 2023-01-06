<?php

namespace Omnipay\imoje;

use CourseLink\Omnipay\HasLanguage;
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
        if(empty($options)){
            $options = json_decode($this->httpRequest->getContent(), true);
        }
        if(empty($headers)){
            $headers = $this->httpRequest->headers->all();
        }

        return new Notification($this, $options, $headers);
    }
}