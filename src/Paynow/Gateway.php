<?php

namespace Omnipay\Paynow;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Paynow\Messages\Notification;
use Omnipay\Paynow\Messages\PurchaseRequest;

class Gateway extends AbstractGateway
{
    use HasPaynowCredentials;

    public function getName(): string
    {
        return 'Paynow';
    }

    public function purchase(array $options = []): AbstractRequest
    {
        return $this->createRequest(PurchaseRequest::class, array_merge($this->getParameters(), $options));
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