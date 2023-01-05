<?php

namespace Omnipay\PayU\Messages;

class TokenRequest extends AbstractRequest
{
    public function getData(): array
    {
        return [
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'grant_type' => 'client_credentials',
        ];
    }

    public function sendData($data): TokenResponse
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getEndpoint() . '/pl/standard/user/oauth/authorize',
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            http_build_query($data),
        );

        $data = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new TokenResponse($this, $data);
    }
}