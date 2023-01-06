<?php

use CourseLink\Omnipay\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupPrzelewy24($this->getHttpClient());
});

it('supports purchase', function () {
    expect($this->gateway->supportsPurchase())->toBeTrue();
});

it('can make a purchase', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/../Mock/PurchaseSuccess.json'));

    $response = $this->gateway->purchase(getPurchaseData())->send();

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->isRedirect())->toBeTrue();
});
