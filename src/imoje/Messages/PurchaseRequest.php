<?php

namespace Omnipay\imoje\Messages;

use CourseLink\Payments\HasCustomer;

class PurchaseRequest extends AbstractRequest
{
    use HasCustomer;

    public function getData()
    {
        return [
            'serviceId' => $this->getServiceId(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency(),
            'title' => $this->getDescription(),
            'returnUrl' => $this->getReturnUrl(),
            'successReturnUrl' => $this->getReturnUrl(),
            'failedReturnUrl' => $this->getCancelUrl(),
            'customer' => [
                'firstName' => $this->getCustomer()->getFirstName(),
                'lastName' => $this->getCustomer()->getLastName(),
                'email' => $this->getCustomer()->getEmail(),
            ]
        ];
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            'post',
            $this->getEndpoint() . $this->getMerchantId() . '/transaction',
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAuthToken(),
            ],
            json_encode($data),
        );
    }
}