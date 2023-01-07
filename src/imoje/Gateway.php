<?php

namespace Omnipay\imoje;

use CourseLink\Omnipay\HasNotificationIPVerification;
use CourseLink\Omnipay\NotificationIPVerificationInterface;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\imoje\Messages\Notification;
use Omnipay\imoje\Messages\PurchaseRequest;

class Gateway extends AbstractGateway implements NotificationIPVerificationInterface
{
    use HasImojeCredentials;
    use HasNotificationIPVerification;

    public function getName(): string
    {
        return 'imoje';
    }

    public function getDefaultNotificationIpAddresses(): array
    {
        return array_merge(
            $this->cidrToArray('5.196.116.32/28'),
            $this->cidrToArray('51.195.95.0/28'),
            $this->cidrToArray('54.37.185.64/28'),
            $this->cidrToArray('54.37.185.80/28'),
            $this->cidrToArray('147.135.151.16/28'),
        );
    }

    public function purchase(array $options = []): RequestInterface
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function acceptNotification(array $options = [], array $headers = []): NotificationInterface
    {
        if (empty($options)) {
            $options = json_decode($this->httpRequest->getContent(), true);
        }
        if (empty($headers)) {
            $headers = $this->httpRequest->headers->all();
        }

        return new Notification($this, $options, $headers);
    }
}