<?php

namespace Omnipay\Przelewy24\Messages;

use Omnipay\Common\Exception\InvalidRequestException;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getSessionId(): string
    {
        return $this->getParameter('sessionId');
    }

    public function setSessionId(string $value): self
    {
        return $this->setParameter('sessionId', $value);
    }

    public function getOriginAmount(): string
    {
        return $this->getParameter('originAmount');
    }

    public function setOriginAmount(string $value): self
    {
        return $this->setParameter('originAmount', $value);
    }

    public function getOrderId(): string
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId(string $value): self
    {
        return $this->setParameter('orderId', $value);
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate(
            'merchantId',
            'posId',
            'sessionId',
            'amount',
            'currency',
            'orderId',
        );

        return [
            'merchantId' => $this->getMerchantId(),
            'posId' => $this->getPosId(),
            'sessionId' => $this->getSessionId(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency(),
            'orderId' => $this->getOrderId(),
            'sign' => $this->getSignature([
                'sessionId' => $this->getSessionId(),
                'orderId' => $this->getOrderId(),
                'amount' => $this->getAmountInteger(),
                'currency' => $this->getCurrency(),
                'crc' => $this->getCrcKey(),
            ]),
        ];
    }

    public function sendData($data): CompletePurchaseResponse
    {
        $httpResponse = $this->sendRequest('PUT', 'api/v1/transaction/verify', $data);

        $data = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}