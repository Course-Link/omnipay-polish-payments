<?php

namespace Omnipay\Paynow;

trait HasPaynowCredentials
{
    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey(string $value): self
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getSignatureKey(): string
    {
        return $this->getParameter('signatureKey');
    }

    public function setSignatureKey(string $value): self
    {
        return $this->setParameter('signatureKey', $value);
    }

    public function calculateSignature(array $data): string
    {
        return base64_encode(hash_hmac('sha256', json_encode($data), $this->getSignatureKey(), true));
    }
}