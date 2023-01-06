<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\Przelewy24\Gateway;

uses(TestCase::class);

function setupPrzelewy24($httpClient): Gateway
{
    $gateway = new Gateway($httpClient);
    $gateway->initialize([
        'posId' => 12345,
        'merchantId' => 12345,
        'crcKey' => 12345,
        'reportKey' => 12345,
        'verify_ip_address' => true,
        'notification_ip_addresses' => [
            '91.216.191.181',
            '91.216.191.182',
            '91.216.191.183',
            '91.216.191.184',
            '91.216.191.185',
            '5.252.202.254',
            '5.252.202.255',
        ],
    ]);
    return $gateway;
}

beforeEach(function () {
    $this->gateway = setupPrzelewy24($this->getHttpClient());
});

it('passes complete gateway test', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/PurchaseSuccess.json'));
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/CompletePurchaseSuccess.json'));

    completeGatewayTest(
        gateway: $this->gateway,
        notificationData: [
            'merchantId' => 12345,
            'posId' => 12345,
            'sessionId' => '12345',
            'amount' => 125,
            'originAmount' => 125,
            'currency' => 'PLN',
            'orderId' => 1,
            'methodId' => 150,
            'statement' => 'Ebook',
            'sign' => '69047ac25e8c15851592d046422e6eade876fc661882a5424480adc3f91ae765fb23b13c3b6bbbec2e6002004b749442',
            'ip_address' => '5.252.202.255'
        ]);
});