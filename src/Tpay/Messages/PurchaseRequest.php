<?php

namespace Omnipay\Tpay\Messages;

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
        return [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
            'lang' => $this->getLanguage(),
            'payer' => [
                'email' => $this->getCustomer()->getEmail(),
                'name' => $this->getCustomer()->getName(),
                'phone' => $this->getCustomer()->getPhone(),
                'address' => $this->getCustomer()->getAddress(),
                'code' => $this->getCustomer()->getPostcode(),
                'city' => $this->getCustomer()->getCity(),
                'country' => $this->getCustomer()->getCountry(),
            ],
            'callbacks' => [
                'payerUrls' => [
                    'success' => $this->getReturnUrl(),
                    'error' => $this->getCancelUrl(),
                ],
                'notification' => [
                    'url' => $this->getNotifyUrl(),
                ]
            ],
            'pay' => [
                'groupId' => $this->getPaymentMethod(),
            ]
        ];
    }

    public function sendData($data): PurchaseResponse
    {
        $httpResponse = $this->httpClient->request(
            'post',
            $this->getEndpoint() . '/transactions',
            [
                'Authorization' => 'Bearer ' . $this->getToken(),
            ],
            json_encode($data),
        );

        $data = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseResponse($this, $data);
    }
}