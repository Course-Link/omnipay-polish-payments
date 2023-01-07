# Omnipay Polish Payments

[![run-tests](https://github.com/Course-Link/omnipay-polish-payments/actions/workflows/run-tests.yml/badge.svg)](https://github.com/Course-Link/omnipay-polish-payments/actions/workflows/run-tests.yml)
[![Total Downloads](http://poser.pugx.org/course-link/omnipay-polish-payments/downloads)](https://packagist.org/packages/course-link/omnipay-polish-payments)
[![PHP Version Require](http://poser.pugx.org/course-link/omnipay-polish-payments/require/php)](https://packagist.org/packages/course-link/omnipay-polish-payments)

Polish Gateways support for the Omnipay PHP payment processing library.

1. [Installation](#installation)
2. [Examples](#examples)
3. [Configuration](#configuration)
    1. [imoje](#imoje)
    2. [Paynow](#paynow)
    3. [PayU](#payu)
    4. [Przelewy24](#przelewy24)
    5. [Tpay](#tpay)

## Installation

```bash
composer require course-link/omnipay-polish-payments
```

## Examples

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
    // This will never happen, since user will always be redirected to off-site payment gateway
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
require_once  __DIR__ . '/vendor/autoload.php';

use Omnipay\Omnipay;

// Select gateway
$gateway = Omnipay::create('imoje');
$gateway = Omnipay::create('Paynow');
$gateway = Omnipay::create('PayU');
$gateway = Omnipay::create('Przelewy24');
$gateway = Omnipay::create('Tpay');
$gateway->initialize([]); // Pass gateway specific options

$notification = $gateway->acceptNotification();

$status = $notification->getTransactionStatus();
$data = $notification->getData();
$transactionReference = $notification->getTransactionReference();

if ($gateway->supportsCompletePurchase()) {
    // This is required for Przelewy24
    $response = $gateway->completePurchase(array_merge($purchaseOptions, $data))->send();
}
```

## Configuration

### imoje

#### Gateway

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('imoje');
$gateway->initialize([
    'merchant_id' => '',
    'service_id' => '',
    'service_key' => '',
    'auth_token' => '',
    'test_mode' => false,
    'verify_ip_address' => true,
    'notification_ip_addresses' => [
    ],
]);
```

#### Purchase

```php
$response = $gateway->purchase([
    'customer' => new Customer([
        'firstName' => 'John', //required
        'lastName' => 'Doe', // required
        'email' => 'johnny@example.com', // required
    ]),
    'language' => 'pl', //required
    'amount' => 99.99, //required
    'description' => 'Ebook',
    'currency' => 'pln', //required
    'transaction_id' => '1', //required
    'notifyUrl' => 'https://example.com/notify-url',
    'returnUrl' => 'https://example.com/return-url',
    'cancelUrl' => 'https://example.com/cancel-url',
])->send();
```
   
#### Notification

```php
$notification = $gateway->acceptNotification(
    options: [], // request body, if empty values are taken from httpRequest
    headers: [] // if empty values are taken from httpRequest
);

$status = $notification->getTransactionStatus();
$data = $notification->getData();
$transactionReference = $notification->getTransactionReference();
```

### Paynow

#### Gateway

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Paynow');
$gateway->initialize([
   'api_key' => '',
   'signature_key' => '',
   'test_mode' => true,
]);
```

#### Purchase

```php
$response = $gateway->purchase([
    'customer' => new Customer([
        'firstName' => 'John',
        'lastName' => 'Doe',
        'email' => 'johnny@example.com', //required
        'phone' => '+48123456789'
    ]),
    'language' => 'pl',
    'amount' => 99.99, //required
    'description' => 'Ebook', //required
    'currency' => 'pln',
    'transaction_id' => '1', //required
    'returnUrl' => 'https://example.com/return-url',
])->send();
```

#### Notification

```php
$notification = $gateway->acceptNotification(
    options: [], // request body, if empty values are taken from httpRequest
    headers: [] // if empty values are taken from httpRequest
);

$status = $notification->getTransactionStatus();
$data = $notification->getData();
$transactionReference = $notification->getTransactionReference();
```

### PayU

#### Http Client Config

PayU requires Http Client to configure:

```php
'allow_redirects' => false,
```

#### Gateway

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('PayU');
$gateway->initialize([
   'pos_id' => '',
   'signature_key' => '',
   'client_id' => '',
   'client_secret' => '',
   'test_mode' => true,
   'verify_ip_address' => true,
   'notification_ip_addresses' => [
   ],
]);
```

#### Purchase

```php
$response = $gateway->purchase([
   'customer' => new Customer([
      'firstName' => 'John', //required
      'lastName' => 'Doe', //required
      'email' => 'johnny@example.com', //required
      'phone' => '+48123456789'
   ]),
   'language' => 'pl',
   'amount' => 99.99, //required
   'description' => 'Ebook', //required
   'client_ip' => '127.0.0.1', //required
   'currency' => 'pln', //required
   'transaction_id' => '1',
   'notifyUrl' => 'https://example.com/notify-url',
   'returnUrl' => 'https://example.com/return-url',
])->send();
```

#### Notification

```php
$notification = $gateway->acceptNotification(
    options: [], // request body, if empty values are taken from httpRequest
    headers: [] // if empty values are taken from httpRequest
);

$status = $notification->getTransactionStatus();
$data = $notification->getData();
$transactionReference = $notification->getTransactionReference();
```

### Przelewy24

#### Gateway

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Przelewy24');
$gateway->initialize([
   'posId' => '',
   'merchantId' => '',
   'crcKey' => '',
   'reportKey' => '',
   'test_mode' => false,
   'verify_ip_address' => true,
   'notification_ip_addresses' => [
   ],
]);
```

#### Purchase

```php
$response = $gateway->purchase([
   'customer' => new Customer([
      'name' => 'John Doe',
      'email' => 'johnny@example.com', //required
      'address' => 'Testowa 25',
      'city' => 'Warszawa',
      'postcode' => '00-000',
      'phone' => '+48123456789'
      'country' => 'PL' //required
   ]),
   'language' => 'pl',
   'amount' => 99.99, //required
   'description' => 'Ebook', //required
   'currency' => 'pln', //required
   'transaction_id' => '1', //required
   'notifyUrl' => 'https://example.com/notify-url',
   'returnUrl' => 'https://example.com/return-url', //required
])->send();
```

#### Notification

```php
$notification = $gateway->acceptNotification(
    options: [], // request body, if empty values are taken from httpRequest
    headers: [] // if empty values are taken from httpRequest
);

// Przelewy24 requires to complete purchase after accepting notification
$response = $gateway->completePurchase(array_merge($purchaseOptions, $notificationData))->send();

$status = $notification->getTransactionStatus();
$data = $notification->getData();
$transactionReference = $notification->getTransactionReference();
```

### Tpay

#### Gateway

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Tpay');
$gateway->initialize([
   'merchant_id' => '',
   'service_id' => '',
   'service_key' => '',
   'auth_token' => '',
   'test_mode' => false,
   'verify_ip_address' => true,
   'notification_ip_addresses' => [
   ],
]);
```

#### Purchase

```php
$response = $gateway->purchase([
   'customer' => new Customer([
      'name' => 'John Doe', //required
      'email' => 'johnny@example.com', //required
      'address' => 'Testowa 25',
      'city' => 'Warszawa',
      'postcode' => '00-000',
      'phone' => '+48123456789'
      'country' => 'PL'
   ]),
   'language' => 'pl',
   'amount' => 99.99, //required
    'description' => 'Ebook', //required
    'currency' => 'pln', //required
   'notifyUrl' => 'https://example.com/notify-url',
   'returnUrl' => 'https://example.com/return-url',
   'cancelUrl' => 'https://example.com/cancel-url',
   'paymentMethod' => 150 // required
])->send();
```

#### Notification

When using Tpay: Regardless of the transaction status (tr_status), the merchant's system should print TRUE response when
all validations are correct.

```php
$notification = $gateway->acceptNotification(
    options: [], // request body, if empty values are taken from httpRequest
    headers: [] // if empty values are taken from httpRequest
);

$status = $notification->getTransactionStatus();
$data = $notification->getData();
$transactionReference = $notification->getTransactionReference();
```