<?php

namespace CourseLink\Omnipay;

use \Omnipay\Common\PaymentMethod as BasePaymentMethod;

class PaymentMethod extends BasePaymentMethod
{
    public function __construct(
        protected         $id,
        protected         $name,
        protected ?string $logoUrl = null,
        protected ?int    $minAmount = null,
        protected ?int    $maxAmount = null,
    )
    {
        parent::__construct($id, $name);
    }

    public function getLogoUrl(): ?string
    {
        return $this->logoUrl;
    }

    public function getMinAmount(): ?int
    {
        return $this->minAmount;
    }

    public function getMaxAmount(): ?int
    {
        return $this->maxAmount;
    }
}