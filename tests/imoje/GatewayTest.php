<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\imoje\Gateway;

uses(TestCase::class);

function setupImoje($httpClient)
{
    $gateway = new Gateway($httpClient);
    $gateway->initialize([
        'merchantId' => 'a7p6g8iumujsjhkh1swc',
        'serviceId' => '31a9f4ec-349e-4bad-ab3b-302cd2750047',
        'serviceKey' => 'm2-u4Bxmd2vWBqpETYxyEXkV7RPtu-j-f1FC',
        'authToken' => 'k0dsj19u9dv17jucxadrdb9ytvpkf9iokku75uk30we2rdb6l7uf0t2i3ligiwqp'
    ]);
    return $gateway;
}

beforeEach(function () {
    $this->gateway = setupImoje($this->getHttpClient());
});

it('passes complete gateway test', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/PurchaseSuccess.json'));

    completeGatewayTest(
        gateway: $this->gateway,
        notificationData: json_decode(file_get_contents(__DIR__ . '/Mock/Notification.json'), true),
        notificationHeaders: [
            'X-IMoje-Signature' => [
                'merchantid' => '6yt3gjt1234f8h9xsdqz',
                'serviceid' => '53f574ed-d4ad-aabe-9981-39ed7584a7b7',
                'signature' => 'ef61b7acc4ddcb086e700e2b411e190ab0950d0dfc84137ecb082290aaf3e5a9',
                'alg' => 'sha256',
            ]
        ],
    );
});