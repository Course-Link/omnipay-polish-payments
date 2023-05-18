<?php

namespace Omnipay\PayU\Messages;

class FetchPaymentMethodsRequest extends AbstractRequest
{
    public function getData(): array
    {
        return [];
    }

    public function sendData($data): FetchPaymentMethodsResponse
    {
        $httpRequest = $this->httpClient->request(
            'get',
            $this->getEndpoint() . '/api/v2_1/paymethods',
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
                'Authorization' => 'Bearer ' . $this->getToken(),
            ]
        );

        $data = json_decode($httpRequest->getBody()->getContents(), true);

        return $this->response = new FetchPaymentMethodsResponse($this, $data);
    }
}