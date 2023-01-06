<?php

namespace Omnipay\Przelewy24\Messages;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->data['data']['status'] === 'success';
    }
}