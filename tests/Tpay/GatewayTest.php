<?php

use CourseLink\Payments\Tests\TestCase;
use GuzzleHttp\Psr7\Response;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Tpay\Gateway;

uses(TestCase::class);

beforeEach(function () {
    $gateway = new Gateway($this->getHttpClient());
    $gateway->initialize([
        'client_id' => '1010-e5736adfd4bc5d8c',
        'client_secret' => '493e01af815383a687b747675010f65d1eefaeb42f63cfe197e7b30f14a556b7',
        'security_code' => 'demo',
        'merchant_id' => '1010',
        'test_mode' => true,
        'verify_ip_address' => true,
        'notification_ip_addresses' => [
            '195.149.229.109',
            '148.251.96.163',
            '178.32.201.77',
            '46.248.167.59',
            '46.29.19.106',
            '176.119.38.175',
        ]
    ]);
    $this->gateway = $gateway;
});

it('creates token', function () {
    $this->mockResponse(mockTokenSuccess());

    $this->gateway->getToken();

    expect($this->gateway->hasToken())->toBeTrue();
});

it('supports purchase', function () {
    expect($this->gateway->supportsPurchase())->toBeTrue();
});

it('can make a purchase', function () {
    $this->mockResponse(mockTokenSuccess());
    $this->mockResponse(mockPurchaseSuccess());

    $response = $this->gateway->purchase([
        'customer' => getValidCustomer(),
        'language' => 'pl',
        'amount' => 99.99,
        'description' => 'Ebook',
        'paymentMethod' => 150,
    ])->send();

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->isRedirect())->toBeTrue();
});

it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {
    $notification = $this->gateway->acceptNotification([
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
        'md5sum' => '81e8007358ef018d170b43472ccc99d9',//TODO
        'ip_address' => '195.149.229.109'
    ]);

    expect($notification->getTransactionStatus())->toEqual(NotificationInterface::STATUS_COMPLETED)
        ->and($notification->getTransactionReference())->toEqual('TR-BRA-TST3X')
        ->and($notification->getMessage())->toEqual('none');
});

function mockTokenSuccess(): Response
{
    return new Response(200, [], file_get_contents(__DIR__ . '/Mock/TokenSuccess.json'));
}

function mockPurchaseSuccess(): Response
{
    return new Response(200, [], file_get_contents(__DIR__ . '/Mock/PurchaseSuccess.json'));
}