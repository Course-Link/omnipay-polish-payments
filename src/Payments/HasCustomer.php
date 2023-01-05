<?php

namespace CourseLink\Payments;

trait HasCustomer
{
    public function getCustomer(): ?Customer
    {
        return $this->getParameter('customer');
    }

    public function setCustomer(Customer|array $customer): self
    {
        if ($customer && ! $customer instanceof Customer) {
            $customer = new Customer($customer);
        }

        return $this->setParameter('customer', $customer);
    }
}