<?php

namespace Omnipay\PayU;

use CourseLink\Omnipay\HasNotificationIPVerification;
use CourseLink\Omnipay\HasOAuth2Token;
use CourseLink\Omnipay\NotificationIPVerificationInterface;
use CourseLink\Omnipay\OAuth2TokenInterface;
use CourseLink\Omnipay\PaymentMethodsInterface;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\PayU\Messages\Notification;
use Omnipay\PayU\Messages\FetchPaymentMethodsRequest;
use Omnipay\PayU\Messages\PurchaseRequest;
use Omnipay\PayU\Messages\TokenRequest;

class Gateway extends AbstractGateway implements OAuth2TokenInterface, NotificationIPVerificationInterface, PaymentMethodsInterface
{
    use HasPayUCredentials;
    use HasOAuth2Token;
    use HasNotificationIPVerification;

    public function getName(): string
    {
        return 'PayU';
    }

    public function getDefaultNotificationIpAddresses(): array
    {
        return $this->getTestMode() ? [
            '185.68.14.10', '185.68.14.11', '185.68.14.12', '185.68.14.26', '185.68.14.27', '185.68.14.28'
        ] : [
            '185.68.12.10', '185.68.12.11', '185.68.12.12', '185.68.12.26', '185.68.12.27', '185.68.12.28'
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

    public function fetchPaymentMethods(array $options = []): AbstractRequest
    {
        return $this->createRequest(FetchPaymentMethodsRequest::class, $options);
    }
}