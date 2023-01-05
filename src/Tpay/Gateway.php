<?php

namespace Omnipay\Tpay;

use CourseLink\Payments\HasOAuth2Token;
use CourseLink\Payments\OAuth2TokenInterface;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Tpay\Messages\PurchaseRequest;
use Omnipay\Tpay\Messages\TokenRequest;
use Omnipay\Tpay\Messages\Notification;

class Gateway extends AbstractGateway implements OAuth2TokenInterface
{
    use HasTpayConfiguration;
    use HasOAuth2Token;

    public function getName(): string
    {
        return 'Tpay';
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

    public function acceptNotification(array $options = []): NotificationInterface
    {
        return new Notification($this, $options);
    }
}