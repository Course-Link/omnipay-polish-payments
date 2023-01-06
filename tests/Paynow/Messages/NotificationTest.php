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
            'HTTP_signature' => 'cHszAC331f+RU8IUSqrscuBwTXkR530/2XcNAzw5bmI='
        ], //Server
        file_get_contents(__DIR__ . '/../Mock/Notification.json') // body
    );
    $this->gateway = setupPaynow($this->getHttpClient(), $request);
});

it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {
    $notification = $this->gateway->acceptNotification();

    expect($notification->getTransactionStatus())->toEqual(NotificationInterface::STATUS_COMPLETED)
        ->and($notification->getTransactionReference())->toEqual('NOGN-4VF-XCR-KY4')
        ->and($notification->getMessage())->toEqual('CONFIRMED');
});