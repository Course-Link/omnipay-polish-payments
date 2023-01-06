<?php

use CourseLink\Omnipay\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->gateway = setupTpay($this->getHttpClient());
});

it('creates token', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/../Mock/TokenSuccess.json'));

    $this->gateway->getToken();

    expect($this->gateway->hasToken())->toBeTrue();
});