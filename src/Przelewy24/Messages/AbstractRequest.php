<?php

namespace Omnipay\Przelewy24\Messages;

use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Omnipay\Przelewy24\HasPrzelewy24Credentials;

abstract class AbstractRequest extends BaseRequest
{
    use HasPrzelewy24Credentials;
}