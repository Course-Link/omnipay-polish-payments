<?php

namespace CourseLink\Omnipay;

interface ExtendedNotificationInterface
{
    public function getTransactionExtendedStatus(): string;
}