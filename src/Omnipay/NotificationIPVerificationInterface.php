<?php

namespace CourseLink\Omnipay;

interface NotificationIPVerificationInterface
{
    public function getVerifyIpAddress(): bool;

    public function setVerifyIpAddress(bool $value): self;

    public function getNotificationIpAddresses(): array;

    public function setNotificationIpAddresses($value): self;

    public function checkNotificationIPAddress(): bool;

    public function getDefaultNotificationIpAddresses(): array;
}