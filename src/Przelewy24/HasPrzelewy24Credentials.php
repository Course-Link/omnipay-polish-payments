<?php

namespace Omnipay\Przelewy24;

trait HasPrzelewy24Credentials
{
    public function getMerchantId(): string
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId(string $value): self
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getPosId(): string
    {
        return $this->getParameter('posId');
    }

    public function setPosId(string $value): self
    {
        return $this->setParameter('posId', $value);
    }

    public function getCrcKey(): string
    {
        return $this->getParameter('crcKey');
    }

    public function setCrcKey(string $value): self
    {
        return $this->setParameter('crcKey', $value);
    }

    public function getReportKey(): string
    {
        return $this->getParameter('reportKey');
    }

    public function setReportKey(string $value): self
    {
        return $this->setParameter('reportKey', $value);
    }

    public function getSignature(array $data): string
    {
        return hash('sha384', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}