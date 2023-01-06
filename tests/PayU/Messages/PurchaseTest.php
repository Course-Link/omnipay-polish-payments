<?php

use CourseLink\Omnipay\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupPayU($this->getHttpClient(), $this->getHttpRequest());
});

it('supports purchase', function () {
    expect($this->gateway->supportsPurchase())->toBeTrue();
});

it('can make a purchase', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/../Mock/TokenSuccess.json'));
    $this->mockResponse(mockJsonResponse(__DIR__ . '/../Mock/PurchaseSuccess.json'));

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