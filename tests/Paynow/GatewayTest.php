<?php

use CourseLink\Payments\Tests\TestCase;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Paynow\Gateway;

uses(TestCase::class);

beforeEach(function () {
    $gateway = new Gateway($this->getHttpClient());
    $gateway->initialize([
        'api_key' => '',
        'signature_key' => '39e20e73-1896-4605-8063-9c966824a681',
        'test_mode' => true,
    ]);
    $this->gateway = $gateway;
});

it('supports purchase', function () {
    expect($this->gateway->supportsPurchase())->toBeTrue();
});

it('can make a purchase', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/PurchaseSuccess.json'));

    $response = $this->gateway->purchase([
        'customer' => getValidCustomer(),
    ])->send();

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->getTransactionReference())->toEqual('NOLV-8F9-08K-WGD')
        ->and($response->isRedirect())->toBeTrue();
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