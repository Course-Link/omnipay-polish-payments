<?php

use CourseLink\Payments\Tests\TestCase;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\PayU\Gateway;

uses(TestCase::class);

beforeEach(function () {
    $gateway = new Gateway($this->getHttpClient());
    $gateway->initialize([
        'pos_id' => 300746,
        'signature_key' => 'b6ca15b0d1020e8094d9b5f8d163db54',
        'client_id' => '300746',
        'client_secret' => '2ee86a66e5d97e3fadc400c9f19b065d',
        'test_mode' => true,
    ]);
    $this->gateway = $gateway;
});

it('supports purchase', function () {
    expect($this->gateway->supportsPurchase())->toBeTrue();
});

it('can make a purchase', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/TokenSuccess.json'));
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/PurchaseSuccess.json'));

    $response = $this->gateway->purchase([
        'customer' => getValidCustomer(),
        'currency' => 'pln',
        'clientIp' => '127.0.0.1',
        'language' => 'pl',
        'amount' => 99.99,
        'description' => 'Ebook',
    ])->send();

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->isRedirect())->toBeTrue();
});

it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {
    $notification = $this->gateway->acceptNotification(
        json_decode(file_get_contents(__DIR__.'/Mock/Notification.json'), true),
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