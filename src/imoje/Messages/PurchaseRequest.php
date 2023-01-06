<?php

namespace Omnipay\imoje\Messages;

use CourseLink\Omnipay\HasCustomer;
use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AbstractRequest
{
    use HasCustomer;

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate(
            'serviceId',
            'amount',
            'currency',
            'transactionId',
            'customer'
        );

        return array_filter([
            'serviceId' => $this->getServiceId(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency(),
            'orderId' => $this->getTransactionId(),
            'title' => $this->getDescription(),
            'returnUrl' => $this->getReturnUrl(),
            'successReturnUrl' => $this->getReturnUrl(),
            'failureReturnUrl' => $this->getCancelUrl(),
            'customer' => [
                'firstName' => $this->getCustomer()->getFirstName(),
                'lastName' => $this->getCustomer()->getLastName(),
                'email' => $this->getCustomer()->getEmail(),
                'locale' => $this->getLanguage()
            ]
        ]);
    }

    public function sendData($data): PurchaseResponse
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

        $data = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseResponse($this, $data);
    }
}