<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\Common\Message\NotificationInterface;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupPayU($this->getHttpClient());
});

it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {
    $notification = $this->gateway->acceptNotification(
        json_decode(file_get_contents(__DIR__.'/../Mock/Notification.json'), true),
        [
            'OpenPayu-Signature' => [
                'signature' => 'e7273a927c8f3605a08945bb886a2317'
            ]
        ]
    );

    expect($notification->getTransactionStatus())->toEqual(NotificationInterface::STATUS_COMPLETED)
        ->and($notification->getTransactionReference())->toEqual('LDLW5N7MF4140324GUEST000P01')
        ->and($notification->getMessage())->toEqual('');
});