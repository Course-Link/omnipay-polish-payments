<?php

use CourseLink\Omnipay\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupPaynow($this->getHttpClient(), $this->getHttpRequest());
});

it('supports purchase', function () {
    expect($this->gateway->supportsPurchase())->toBeTrue();
});

it('can make a purchase', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/../Mock/PurchaseSuccess.json'));

    $response = $this->gateway->purchase([
        'customer' => getValidCustomer(),
        'language' => 'pl',
    ])->send();

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->getTransactionReference())->toEqual('NOLV-8F9-08K-WGD')
        ->and($response->isRedirect())->toBeTrue();
});