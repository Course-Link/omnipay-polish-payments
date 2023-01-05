<?php

use CourseLink\Payments\Tests\TestCase;
use Omnipay\imoje\Gateway;

uses(TestCase::class);

beforeEach(function () {
    $gateway = new Gateway($this->getHttpClient());
    $gateway->initialize([

    ]);
    $this->gateway = $gateway;
});

it('supports purchase', function () {
    expect($this->gateway->supportsPurchase())->toBeTrue();
});

it('can make a purchase', function () {

});

it('supports accepting notification', function () {
    expect($this->gateway->supportsAcceptNotification())->toBeTrue();
});

it('can accept a notification', function () {

});