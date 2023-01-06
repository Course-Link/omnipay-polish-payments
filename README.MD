# Omnipay Polish Payments

[![run-tests](https://github.com/Course-Link/omnipay-polish-payments/actions/workflows/run-tests.yml/badge.svg)](https://github.com/Course-Link/omnipay-polish-payments/actions/workflows/run-tests.yml)
[![Total Downloads](http://poser.pugx.org/course-link/omnipay-polish-payments/downloads)](https://packagist.org/packages/course-link/omnipay-polish-payments)
[![PHP Version Require](http://poser.pugx.org/course-link/omnipay-polish-payments/require/php)](https://packagist.org/packages/course-link/omnipay-polish-payments)

Polish Gateways support for the Omnipay PHP payment processing library.

## Installation

```bash
composer require course-link/omnipay-polish-payments
```

## Example

Here is a simple example of how to use Omnipay. As you can see, Omnipay has a consistent, well thought out API. As much
as possible, we try to abstract the differences between the various payments gateways.

### Making a purchase

```php
require_once  __DIR__ . '/vendor/autoload.php';

use Omnipay\Omnipay;
use CourseLink\Omnipay\Customer;

// Select gateway
$gateway = Omnipay::create('imoje');
$gateway = Omnipay::create('Paynow');
$gateway = Omnipay::create('PayU');
$gateway = Omnipay::create('Przelewy24');
$gateway = Omnipay::create('Tpay');
$gateway->initialize([]); // Pass gateway specific options

// Send purchase request
$response = $gateway->purchase([
    // Minimum options to use with any of package gateways
    'customer' => new Customer([
        'name' => 'John Doe',
        'firstName' => 'John',
        'lastName' => 'Doe',
        'email' => 'johnny@example.com',
        'address' => 'Testowa 25',
        'city' => 'Warszawa',
        'postcode' => '00-000',
        'country' => 'PL'
    ]),
    'language' => 'pl',
    'amount' => 99.99,
    'description' => 'Ebook',
    'client_ip' => '127.0.0.1',
    'currency' => 'pln',
    'transaction_id' => '1',
    'notifyUrl' => 'https://example.com/notify-url',
    'paymentMethod' => 150 // required for tpay gateway
])->send();

// Process response
if ($response->isSuccessful()) {
    // Payment was successful
    print_r($response);
} elseif ($response->isRedirect()) {
    // Redirect to offsite payment gateway
    $response->redirect();
} else {
    // Payment failed
    echo $response->getMessage();
}
```

### Accepting notification from gateway

```php
// TODO
```