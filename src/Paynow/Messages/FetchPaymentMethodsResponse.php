<?php

namespace Omnipay\Paynow\Messages;

use CourseLink\Omnipay\PaymentMethod;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\FetchPaymentMethodsResponseInterface;

class FetchPaymentMethodsResponse extends AbstractResponse implements FetchPaymentMethodsResponseInterface
{
    public function isSuccessful(): bool
    {
        return isset($this->data);
    }

    public function getPaymentMethods(): array
    {
        $methods = [];

        foreach ($this->data as $paymentMethods) {
            if ($paymentMethods['type'] !== 'PBL') {
                continue;
            }

            foreach ($paymentMethods['paymentMethods'] as $method) {
                $methods[] = new PaymentMethod(
                    $method['id'],
                    $method['name'],
                    $method['image'] ?? null,
                );
            }
        }

        return $methods;
    }
}