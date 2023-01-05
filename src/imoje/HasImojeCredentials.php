<?php

namespace Omnipay\imoje;

trait HasImojeCredentials
{
    public function getMerchantId(): string
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId(string $value): self
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getServiceId(): string
    {
        return $this->getParameter('serviceId');
    }

    public function setServiceId(string $value): self
    {
        return $this->setParameter('serviceId', $value);
    }

    public function getServiceKey(): string
    {
        return $this->getParameter('serviceKey');
    }

    public function setServiceKey(string $value): self
    {
        return $this->setParameter('serviceKey', $value);
    }

    public function getAuthToken(): string
    {
        return $this->getParameter('authToken');
    }

    public function setAuthToken(string $value): self
    {
        return $this->setParameter('authToken', $value);
    }
}