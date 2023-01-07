<?php

namespace Omnipay\Tpay;

use CourseLink\Omnipay\HasNotificationIPVerification;
use CourseLink\Omnipay\HasOAuth2Token;
use CourseLink\Omnipay\NotificationIPVerificationInterface;
use CourseLink\Omnipay\OAuth2TokenInterface;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Tpay\Messages\PurchaseRequest;
use Omnipay\Tpay\Messages\TokenRequest;
use Omnipay\Tpay\Messages\Notification;

class Gateway extends AbstractGateway implements OAuth2TokenInterface, NotificationIPVerificationInterface
{
    use HasTpayConfiguration;
    use HasOAuth2Token;
    use HasNotificationIPVerification;

    public function getName(): string
    {
        return 'Tpay';
    }

    public function getDefaultNotificationIpAddresses(): array
    {
        return [
            '195.149.229.109',
            '148.251.96.163',
            '178.32.201.77',
            '46.248.167.59',
            '46.29.19.106',
            '176.119.38.175',
        ];
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
        if (empty($options)) {
            parse_str($this->httpRequest->getContent(), $options);
        }

        return new Notification($this, $options);
    }
}