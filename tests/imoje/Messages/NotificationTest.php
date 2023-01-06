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
            'HTTP_x-imoje-signature' => 'merchantid=6yt3gjtm9p7b8h9xsdqz;serviceid=63f574ed-d4ad-407e-9981-39ed7584a7b7;signature=2ad3293ff698515e28ffc33505b41c51c8a9e2ad3f550997dc5ecf88b0b8fb44;alg=sha256'
        ], //Server
        file_get_contents(__DIR__ . '/../Mock/Notification.json') // body
    );
    $this->gateway = setupImoje($this->getHttpClient(), $request);
});


it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {
    $notification = $this->gateway->acceptNotification();

    expect($notification->getTransactionStatus())->toEqual(NotificationInterface::STATUS_COMPLETED)
        ->and($notification->getTransactionReference())->toEqual('07938437-cae3-4d46-877d-e1b9d6e6c58f')
        ->and($notification->getMessage())->toEqual('');
});