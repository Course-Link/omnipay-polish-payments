<?php

use CourseLink\Omnipay\Tests\TestCase;
use Omnipay\Paynow\Gateway;

uses(TestCase::class);

function setupPaynow($httpClient, $httpRequest)
{
    $gateway = new Gateway($httpClient, $httpRequest);
    $gateway->initialize([
        'api_key' => '',
        'signature_key' => 'b69306b3-4267-4dd9-8950-d488f8ed3a71',
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
            'HTTP_signature' => 'cHszAC331f+RU8IUSqrscuBwTXkR530/2XcNAzw5bmI='
        ], //Server
        file_get_contents(__DIR__ . '/Mock/Notification.json') // body
    );
    $this->gateway = setupPaynow($this->getHttpClient(), $request);
});

it('passes complete gateway test', function () {
    $this->mockResponse(mockJsonResponse(__DIR__ . '/Mock/PurchaseSuccess.json'));

    completeGatewayTest(
        gateway: $this->gateway,
        notificationHeaders: [], notificationData: []
    );
});