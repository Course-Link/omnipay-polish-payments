<?php

namespace Omnipay\imoje\Messages;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\imoje\Gateway;

class Notification implements NotificationInterface
{
    public function __construct(
        protected Gateway $gateway,
        protected array   $data,
    )
    {
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }

    public function getTransactionReference()
    {
        // TODO: Implement getTransactionReference() method.
    }

    public function getTransactionStatus()
    {
        // TODO: Implement getTransactionStatus() method.
    }

    public function getMessage()
    {
        // TODO: Implement getMessage() method.
    }
}