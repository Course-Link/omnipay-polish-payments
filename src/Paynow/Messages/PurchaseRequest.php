<?php

namespace Omnipay\Paynow\Messages;

use CourseLink\Omnipay\HasCustomer;

class PurchaseRequest extends AbstractRequest
{
    use HasCustomer;

    public function getData(): array
    {
        return [
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency(),
            'externalId' => $this->getTransactionId(),
            'description' => $this->getDescription(),
            'continueUrl' => $this->getReturnUrl(),
            'buyer' => [
                'email' => $this->getCustomer()->getEmail(),
                'firstName' => $this->getCustomer()->getFirstName(),
                'lastName' => $this->getCustomer()->getLastName(),
                'phone' => $this->getCustomer()->getPhone(),
                'locale' => $this->getLanguageBCP47(),
            ],
        ];
    }

    public function sendData($data): PurchaseResponse
    {
        $httpResponse = $this->httpClient->request(
            'post',
            $this->getEndpoint() . 'v1/payments',
            [
                'Api-Key' => $this->getApiKey(),
                'Signature' => $this->calculateSignature($data),
                'Idempotency-Key' => substr(uniqid($this->getTransactionId() . '_'), 0, 50),
            ],
            json_encode($data),
        );

        $data = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseResponse($this, $data);
    }
}