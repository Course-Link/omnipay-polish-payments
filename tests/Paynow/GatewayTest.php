<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\Paynow\Gateway;

uses(TestCase::class);

function setupPaynow($httpClient)
{
    $gateway = new Gateway($httpClient);
    $gateway->initialize([
        'api_key' => '',
        'signature_key' => '39e20e73-1896-4605-8063-9c966824a681',
        'test_mode' => true,
    ]);
    return $gateway;
}

beforeEach(function () {
    $this->gateway = setupPaynow($this->getHttpClient());
});

it('passes complete gateway test', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/PurchaseSuccess.json'));

    completeGatewayTest(
        gateway: $this->gateway,
        notificationData: [
            "paymentId" => "NOLV-8F9-08K-WGD",
            "externalId" => "9fea23c7-cd5c-4884-9842-6f8592be65df",
            "status" => "CONFIRMED",
            "modifiedAt" => "2018-12-12T13:24:52"
        ],
        notificationHeaders: [
            'Signature' => 'PXj9lVph0QhBph6ArGucdRS0GwhWVRmueiZ+aO6AuVw='
        ]);
});