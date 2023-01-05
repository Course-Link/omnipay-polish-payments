<?php

namespace Omnipay\Przelewy24\Messages;

use CourseLink\Payments\HasCustomer;

class PurchaseRequest extends AbstractRequest
{
    use HasCustomer;

    public function getData()
    {
        return [
            'p24_session_id' => $this->getTransactionId(),
            'p24_currency' => $this->getCurrency(),
            'p24_email' => $this->getCustomer()->getEmail(),
            'p24_url_return' => $this->getReturnUrl(),
            'p24_url_status' => $this->getNotifyUrl(),
            'p24_language' => $this->getLanguage(),
            'p24_description' => $this->getDescription(),
            'p24_client' => $this->getCustomer()->getName(),
            'p24_address' => $this->getCustomer()->getAddress(),

        ];
    }

    public function sendData($data)
    {
        // TODO: Implement sendData() method.
    }
}