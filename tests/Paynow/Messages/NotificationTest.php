<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\Common\Message\NotificationInterface;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupPaynow($this->getHttpClient());
});

it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {
    $notification = $this->gateway->acceptNotification([
        "paymentId" => "NOLV-8F9-08K-WGD",
        "externalId" => "9fea23c7-cd5c-4884-9842-6f8592be65df",
        "status" => "CONFIRMED",
        "modifiedAt" => "2018-12-12T13:24:52"
    ], [
        'Signature' => 'PXj9lVph0QhBph6ArGucdRS0GwhWVRmueiZ+aO6AuVw='
    ]);

    expect($notification->getTransactionStatus())->toEqual(NotificationInterface::STATUS_COMPLETED)
        ->and($notification->getTransactionReference())->toEqual('NOLV-8F9-08K-WGD')
        ->and($notification->getMessage())->toEqual('CONFIRMED');
});