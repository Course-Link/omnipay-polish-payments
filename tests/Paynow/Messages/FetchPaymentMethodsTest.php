<?php

use CourseLink\Omnipay\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupPaynow($this->getHttpClient(), $this->getHttpRequest());
});

it('can fetch payment methods', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/../Mock/PaymentMethods.json'));

    $response = $this->gateway->fetchPaymentMethods()->send();

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->getPaymentMethods())->toBeArray()
        ->and($response->getPaymentMethods())->toHaveCount(1);
});