<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Tpay\Gateway;

uses(TestCase::class);

beforeEach(function () {
    $request = $this->getHttpRequest();
    $request->initialize(
        [], //GET
        [], //POST
        [], //Attributes,
        [], //Cookies
        [], //Files,
        [], //Server
        http_build_query([
            'id' => 1010,
            'tr_id' => 'TR-BRA-TST3X',
            'tr_date' => '2022-12-28 17:27:31',
            'tr_crc' => 'test',
            'tr_amount' => 25.55,
            'tr_paid' => 25.55,
            'tr_desc' => 'Ebook',
            'tr_status' => 'TRUE',
            'tr_error' => 'none',
            'tr_email' => 'johnny@example.com',
            'md5sum' => '81e8007358ef018d170b43472ccc99d9',
        ])
    );
    $this->gateway = setupTpay($this->getHttpClient(), $request);
});

it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {
    $notification = $this->gateway->acceptNotification();

    expect($notification->getTransactionStatus())->toEqual(NotificationInterface::STATUS_COMPLETED)
        ->and($notification->getTransactionReference())->toEqual('TR-BRA-TST3X')
        ->and($notification->getMessage())->toEqual('none');
});