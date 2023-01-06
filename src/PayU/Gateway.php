<?php

namespace Omnipay\PayU;

use CourseLink\Omnipay\HasOAuth2Token;
use CourseLink\Omnipay\OAuth2TokenInterface;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\PayU\Messages\Notification;
use Omnipay\PayU\Messages\PurchaseRequest;
use Omnipay\PayU\Messages\TokenRequest;

class Gateway extends AbstractGateway implements OAuth2TokenInterface
{
    use HasPayUCredentials;
    use HasOAuth2Token;

    public function getName(): string
    {
        return 'PayU';
    }

    public function createRequest($class, array $parameters): AbstractRequest
    {
        if (!$this->hasToken() && $class !== TokenRequest::class) {
            $this->getToken();
        }

        return parent::createRequest($class, $parameters);
    }

    public function createToken(): AbstractRequest
    {
        return $this->createRequest(TokenRequest::class, []);
    }

    public function purchase(array $options = []): AbstractRequest
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