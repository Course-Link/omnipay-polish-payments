<?php

use CourseLink\Omnipay\Customer;
use GuzzleHttp\Psr7\Response;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;

function getPurchaseData(): array
{
    return [
        'customer' => getValidCustomer(),
        'language' => 'pl',
        'amount' => 99.99,
        'description' => 'Ebook',
        'client_ip' => '127.0.0.1',
        'currency' => 'pln',
        'transaction_id' => '1',
        'notifyUrl' => 'https://example.com/notify-url'
    ];
}

function getValidCustomer(): Customer
{
    return new Customer([
        'name' => 'John Doe',
        'firstName' => 'John',
        'lastName' => 'Doe',
        'email' => 'johnny@example.com',
        'address' => 'Testowa 25',
        'city' => 'Warszawa',
        'postcode' => '00-000',
        'country' => 'PL'
    ]);
}

function completeGatewayTest(
    AbstractGateway $gateway,
    array           $notificationData,
    array           $notificationHeaders = [],

): void
{
    expect($gateway->supportsPurchase())->toBeTrue()
        ->and($gateway->supportsAcceptNotification())->toBeTrue();

    $response = $gateway->purchase(getPurchaseData())->send();

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->isRedirect())->toBeTrue();

    $notification = $gateway->acceptNotification($notificationData, $notificationHeaders);

    expect($notification->getTransactionStatus())->toEqual(NotificationInterface::STATUS_COMPLETED);

    if ($gateway->supportsCompletePurchase()) {
        $response = $gateway->completePurchase(array_merge(getPurchaseData(), $notificationData))->send();

        expect($response->isSuccessful())->toBeTrue();
    }
}

function mockJsonResponse(string $path, array $headers = []): Response
{
    return new Response(200, $headers, file_get_contents($path));
}