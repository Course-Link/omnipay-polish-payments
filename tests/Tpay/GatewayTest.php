<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\Tpay\Gateway;

uses(TestCase::class);

function setupTpay($httpClient)
{
    $gateway = new Gateway($httpClient);
    $gateway->initialize([
        'client_id' => '1010-e5736adfd4bc5d8c',
        'client_secret' => '493e01af815383a687b747675010f65d1eefaeb42f63cfe197e7b30f14a556b7',
        'security_code' => 'demo',
        'merchant_id' => '1010',
        'test_mode' => true,
        'verify_ip_address' => true,
        'notification_ip_addresses' => [
            '195.149.229.109',
            '148.251.96.163',
            '178.32.201.77',
            '46.248.167.59',
            '46.29.19.106',
            '176.119.38.175',
        ]
    ]);

    return $gateway;
}

beforeEach(function () {
    $this->gateway = setupTpay($this->getHttpClient());
});

it('passes complete gateway test', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/TokenSuccess.json'));
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/PurchaseSuccess.json'));

    completeGatewayTest(
        gateway: $this->gateway,
        notificationData: [
            'id' => 1010,
            'tr_id' => 'TR-BRA-TST3X',
            'tr_date' => '2022-12-28 17:27:31',
            'tr_crc' => 'test',
            'tr_amount' => 25.55,
            'tr_paid' => 25.55,
            'tr_desc' => 'Ebook',
            'tr_status' => 'TRUE',
            'tr_error' => 'none',
            'tr_email' => 'johnny@example.com',
            'md5sum' => '81e8007358ef018d170b43472ccc99d9',
            'ip_address' => '195.149.229.109'
        ]);
});