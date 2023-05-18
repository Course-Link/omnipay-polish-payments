<?php

namespace Omnipay\Paynow\Messages;

class FetchPaymentMethodsRequest extends AbstractRequest
{
    public function getData(): array
    {
        return [];
    }

    public function sendData($data): FetchPaymentMethodsResponse
    {
        $httpResponse = $this->httpClient->request(
            'get',
            $this->getEndpoint() . 'v2/payments/paymentmethods',
            [
                'Api-Key' => $this->getApiKey(),
                'Signature' => $this->calculateSignature($data),
                'Accept' => 'application/json',
            ],
            json_encode($data),
        );

        $data = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new FetchPaymentMethodsResponse($this, $data);
    }
}