<?php

use CourseLink\Payments\Tests\TestCase;
use Omnipay\Przelewy24\Gateway;

uses(TestCase::class);

beforeEach(function () {
    $gateway = new Gateway($this->getHttpClient());
    $gateway->initialize([

    ]);
    $this->gateway = $gateway;
});

//it('supports purchase', function () {
//    expect($this->gateway->supportsPurchase())->toBeTrue();
//});
//
//it('supports accepting notification', function () {
//    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
//});