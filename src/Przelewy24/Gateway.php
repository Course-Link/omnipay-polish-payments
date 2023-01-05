<?php

namespace Omnipay\Przelewy24;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    use HasPrzelewy24Credentials;

    public function getName(): string
    {
        return 'PayU';
    }
}