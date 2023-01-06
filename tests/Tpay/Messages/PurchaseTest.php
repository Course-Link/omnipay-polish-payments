<?php

use CourseLink\Omnipay\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupTpay($this->getHttpClient());
});

it('supports purchase', function () {
    expect($this->gateway->supportsPurchase())->toBeTrue();
});

it('can make a purchase', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/../Mock/TokenSuccess.json'));
    $this->mockResponse(mockJsonResponse(__DIR__ . '/../Mock/PurchaseSuccess.json'));

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