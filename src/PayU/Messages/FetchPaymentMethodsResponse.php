<?php

namespace Omnipay\PayU\Messages;

use CourseLink\Omnipay\PaymentMethod;
use CourseLink\Omnipay\PaymentMethodsInterface;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\FetchPaymentMethodsResponseInterface;

class FetchPaymentMethodsResponse extends AbstractResponse implements FetchPaymentMethodsResponseInterface
{
    public function isSuccessful(): bool
    {
        return isset($this->data['payByLinks']);
    }

    /**
     * @return array<int, PaymentMethod>
     */
    public function getPaymentMethods(): array
    {
        $methods = $this->data['payByLinks'];

        return array_filter(array_map(function (array $method) {
            if ($method['status'] !== 'ENABLED') {
                return null;
            }

            return new PaymentMethod(
                $method['value'],
                $method['name'],
                $method['brandImageUrl'] ?? null,
                $method['minAmount'] ?? null,
                $method['maxAmount'] ?? null,
            );
        }, $methods));
    }
}