<?php

namespace Omnipay\Przelewy24\Messages;

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
            'merchantId',
            'posId',
            'transactionId',
            'amount',
            'currency',
            'description',
            'customer',
            'language',
            'notifyUrl',
            'crcKey',
        );

        return [
            'merchantId' => $this->getMerchantId(),
            'posId' => $this->getPosId(),
            'sessionId' => $this->getTransactionId(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
            'email' => $this->getCustomer()->getEmail(),
            'client' => $this->getCustomer()->getName(),
            'address' => $this->getCustomer()->getAddress(),
            'zip' => $this->getCustomer()->getPostcode(),
            'city' => $this->getCustomer()->getCity(),
            'country' => $this->getCustomer()->getCountry(),
            'phone' => $this->getCustomer()->getPhone(),
            'language' => $this->getLanguage(),
            'urlReturn' => $this->getReturnUrl(),
            'urlStatus' => $this->getNotifyUrl(),
            'sign' => $this->getSignature([
                'sessionId' => $this->getTransactionId(),
                'merchantId' => $this->getMerchantId(),
                'amount' => $this->getAmountInteger(),
                'currency' => $this->getCurrency(),
                'crc' => $this->getCrcKey(),
            ]),
        ];
    }

    public function sendData($data): PurchaseResponse
    {
        $httpResponse = $this->sendRequest('POST', 'api/v1/transaction/register', $data);

        $data = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseResponse($this, $data);
    }
}