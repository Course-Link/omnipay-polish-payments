<?php

namespace Omnipay\PayU\Messages;

use CourseLink\Payments\HasCustomer;
use GuzzleHttp\Client;

class PurchaseRequest extends AbstractRequest
{
    use HasCustomer;

    public function getData(): array
    {
        return [
            'notifyUrl' => $this->getNotifyUrl(),
            'customerIp' => $this->getClientIp(),
            'merchantPosId' => $this->getPosId(),
            'description' => $this->getDescription(),
            'currencyCode' => $this->getCurrency(),
            'totalAmount' => $this->getAmountInteger(),
            'continueUrl' => $this->getReturnUrl(),
            'products' => [

            ],
            'buyer' => [
                'email' => $this->getCustomer()->getEmail(),
                'phone' => $this->getCustomer()->getPhone(),
                'firstName' => $this->getCustomer()->getFirstName(),
                'lastName' => $this->getCustomer()->getLastName(),
                'language' => $this->getLanguage(),
            ]
        ];
    }

    public function sendData($data): PurchaseResponse
    {
        $httpResponse = $this->httpClient->request(
            'post',
            $this->getEndpoint() . '/api/v2_1/orders',
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getToken(),
            ],
            json_encode($data),
        );

        $data = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseResponse($this, $data);
    }
}