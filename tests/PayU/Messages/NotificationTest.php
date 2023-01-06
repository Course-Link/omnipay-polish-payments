<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\Common\Message\NotificationInterface;

uses(TestCase::class);

beforeEach(function () {
    $request = $this->getHttpRequest();
    $request->initialize(
        [], //GET
        [], //POST
        [], //Attributes,
        [], //Cookies
        [], //Files,
        [
            'HTTP_openpayu-signature' => 'sender=checkout;signature=fc31745fb86a5a4b8f011efc005be308;algorithm=MD5;content=DOCUMENT'
        ], //Server
        file_get_contents(__DIR__ . '/../Mock/Notification.json') // body
    );
    $this->gateway = setupPayU($this->getHttpClient(), $request);
});

it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {
    $notification = $this->gateway->acceptNotification();

    expect($notification->getTransactionStatus())->toEqual(NotificationInterface::STATUS_COMPLETED)
        ->and($notification->getTransactionReference())->toEqual('NV61K1D7P4230106GUEST000P01')
        ->and($notification->getMessage())->toEqual('');
});