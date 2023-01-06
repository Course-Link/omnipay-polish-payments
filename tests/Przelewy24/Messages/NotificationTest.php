<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\Common\Message\NotificationInterface;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupPrzelewy24($this->getHttpClient());
});

it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {
    $notification = $this->gateway->acceptNotification([
        'merchantId' => 12345,
        'posId' => 12345,
        'sessionId' => '12345',
        'amount' => 125,
        'originAmount' => 125,
        'currency' => 'PLN',
        'orderId' => 1,
        'methodId' => 150,
        'statement' => 'Ebook',
        'sign' => '69047ac25e8c15851592d046422e6eade876fc661882a5424480adc3f91ae765fb23b13c3b6bbbec2e6002004b749442',
        'ip_address' => '5.252.202.255'
    ]);

    expect($notification->getTransactionStatus())->toEqual(NotificationInterface::STATUS_COMPLETED)
        ->and($notification->getTransactionReference())->toEqual('1')
        ->and($notification->getMessage())->toEqual('');
});