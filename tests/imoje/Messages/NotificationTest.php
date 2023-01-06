<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\Common\Message\NotificationInterface;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupImoje($this->getHttpClient());
});

it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {
    $notification = $this->gateway->acceptNotification(
        json_decode(file_get_contents(__DIR__ . '/../Mock/Notification.json'), true)
        , [
            'X-IMoje-Signature' => [
                'merchantid' => '6yt3gjt1234f8h9xsdqz',
                'serviceid' => '53f574ed-d4ad-aabe-9981-39ed7584a7b7',
                'signature' => 'ef61b7acc4ddcb086e700e2b411e190ab0950d0dfc84137ecb082290aaf3e5a9',
                'alg' => 'sha256',
            ]
        ],
    );

    expect($notification->getTransactionStatus())->toEqual(NotificationInterface::STATUS_COMPLETED)
        ->and($notification->getTransactionReference())->toEqual('07938437-cae3-4d46-877d-e1b9d6e6c58f')
        ->and($notification->getMessage())->toEqual('');
});