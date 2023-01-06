<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\PayU\Gateway;

uses(TestCase::class);

function setupPayU($httpClient, $httpRequest): Gateway
{
    $gateway = new Gateway($httpClient, $httpRequest);
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
    $request = $this->getHttpRequest();
    $request->initialize(
        [], //GET
        [], //POST
        [], //Attributes,
        [], //Cookies
        [], //Files,
        [
            'HTTP_openpayu-signature' => 'sender=checkout;signature=fc31745fb86a5a4b8f011efc005be308;algorithm=MD5;content=DOCUMENT'
        ], //Server
        file_get_contents(__DIR__ . '/Mock/Notification.json') // body
    );
    $this->gateway = setupPayU($this->getHttpClient(), $request);
});

it('passes complete gateway test', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/TokenSuccess.json'));
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/PurchaseSuccess.json'));

    completeGatewayTest(
        gateway: $this->gateway,
        notificationData: [],
        notificationHeaders: [],
    );
});