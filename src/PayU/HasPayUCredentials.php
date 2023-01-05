<?php

namespace Omnipay\PayU;

trait HasPayUCredentials
{
    public function getPosId(): string
    {
        return $this->getParameter('posId');
    }

    public function setPosId(string $value): self
    {
        return $this->setParameter('posId', $value);
    }

    public function getSignatureKey(): string
    {
        return $this->getParameter('signatureKey');
    }

    public function setSignatureKey(string $value): self
    {
        return $this->setParameter('signatureKey', $value);
    }
}