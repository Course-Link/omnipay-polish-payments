<?php

use CourseLink\Omnipay\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupPrzelewy24($this->getHttpClient());
});

it('supports completing purchase', function () {
    expect($this->gateway->supportsCompletePurchase())->toBeTrue();
});

it('can complete a purchase', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/../Mock/CompletePurchaseSuccess.json'));

    $response = $this->gateway->completePurchase([
        'merchantId' => 12345,
        'posId' => 12345,
        'sessionId' => '12345',
        'amount' => 125,
        'originAmount' => 125,
        'currency' => 'PLN',
        'orderId' => 1,
        'methodId' => 150,
        'statement' => 'Ebook',
        'sign' => '0a44f347a3eb57b2f81beeabb7568508c4f6517a5437f5b23f112a3b099f38945c1b167eae661702642f9fde6ebd041f',
    ])->send();

    expect($response->isSuccessful())->toBeTrue();
});