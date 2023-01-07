<?php

namespace Omnipay\Tpay;

trait HasTpayConfiguration
{
    public function getMerchantId(): string
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId(string $value): self
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getSecurityCode(): string
    {
        return $this->getParameter('securityCode');
    }

    public function setSecurityCode(string $value): self
    {
        return $this->setParameter('securityCode', $value);
    }
}