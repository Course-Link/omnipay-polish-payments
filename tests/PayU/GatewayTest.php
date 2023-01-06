<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\PayU\Gateway;

uses(TestCase::class);

function setupPayU($httpClient): Gateway
{
    $gateway = new Gateway($httpClient);
    $gateway->initialize([
        'pos_id' => 300746,
        'signature_key' => 'b6ca15b0d1020e8094d9b5f8d163db54',
        'client_id' => '300746',
        'client_secret' => '2ee86a66e5d97e3fadc400c9f19b065d',
        'test_mode' => true,
    ]);
    return $gateway;
}

beforeEach(function () {
    $this->gateway = setupPayU($this->getHttpClient());
});

it('passes complete gateway test', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/TokenSuccess.json'));
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/PurchaseSuccess.json'));

    completeGatewayTest(
        gateway: $this->gateway,
        notificationData: json_decode(file_get_contents(__DIR__ . '/Mock/Notification.json'), true),
        notificationHeaders: [
            'OpenPayu-Signature' => [
                'signature' => 'e7273a927c8f3605a08945bb886a2317'
            ]
        ]);
});