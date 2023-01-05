<?php

use CourseLink\Payments\Customer;
use GuzzleHttp\Psr7\Response;

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

function mockJsonResponse(string $path, array $headers = []): Response
{
    return new Response(200, $headers, file_get_contents($path));
}