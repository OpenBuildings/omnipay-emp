Omnipay: EMP
============

**eMerchantPay driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/clippings/omnipay-emp.png?branch=master)](https://travis-ci.org/clippings/omnipay-emp)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/clippings/omnipay-emp/badges/quality-score.png)](https://scrutinizer-ci.com/g/clippings/omnipay-emp/)
[![Code Coverage](https://scrutinizer-ci.com/g/clippings/omnipay-emp/badges/coverage.png)](https://scrutinizer-ci.com/g/clippings/omnipay-emp/)
[![Latest Stable Version](https://poser.pugx.org/clippings/omnipay-emp/v/stable.png)](https://packagist.org/packages/clippings/omnipay-emp)

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements eMerchantPay support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "clippings/omnipay-emp": "~0.1"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* eMerchantPay

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

In order to use this gateway, you need to provide apiKey and clientId.

```php
$gateway = Omnipay::create('eMerchantPay');
$gateway->setApiKey('abc123');
$gateway->setClientId('abc123');
```

You can additionally configure Threatmatrix:

```php
$threatmatrix = new Threatmatrix('organiazation id', 'client id');
$gateway->setThreatmatrix($threatmatrix);
```

For a successful purchase you need to provide  ``transactionReference``,``currency``,``clientIp``,``card`` and``items``:

```php
$purchase = $gateway->purchase(array(
    'currency' => 'GBP',
    'transactionReference' => 'referenceID1',
    'clientIp' => '95.87.212.88',
    'items' => array(
        array(
            'name' => 10,
            'price' => '5.00',
            'description' => 'Product 1 Desc',
            'quantity' => 2
        ),
        array(
            'name' => 12,
            'price' => '5.00',
            'description' => 'Shipping for Product 1',
            'quantity' => 1
        ),
        array(
            'name' => 12,
            'price' => '0.00',
            'description' => 'Promotion',
            'quantity' => 1
        ),
    ),
    'card' => array(
        'firstName' => 'Example',
        'lastName' => 'User',
        'number' => '4111111111111111',
        'expiryMonth' => 7,
        'expiryYear' => 2013,
        'cvv' => 123,
        'address1' => '123 Shipping St',
        'address2' => 'Shipsville',
        'city' => 'Shipstown',
        'postcode' => '54321',
        'state' => 'NY',
        'country' => 'US',
        'phone' => '(555) 987-6543',
        'email' => 'john@example.com',
    )
));
```

All of the fields above are supported, and depending on your eMerchantPay configuration - required. Items with negative prices are also supported. The name of each item must be a unique identifier. This is used for refunds later.

A full refund example:

```php
$refund = $gateway->refund(array(
    'amount' => '200.00',
    'description' => 'Faulty Product',
    'transactionReference' => '51711614',
    'transactionId' => '1413980404',
));
```

You can also do partial refunds by providing the items directly. This type of refund ignores "amount":

```php
$refund = $gateway->refund(array(
    'items' => array(
        array(
            'name' => '51945994',
            'price' => '10.00',
        ),
        array(
            'name' => '51946004',
            'price' => '5.00',
        )
    ),
    'description' => 'Faulty Product',
    'transactionReference' => '51711614',
    'transactionId' => '1413980404',
));
```

Where item "name" is the id, given by eMerchantPay in the data of the purchase response.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/clippings/omnipay-emp/issues),
or better yet, fork the library and submit a pull request.

License
-------

Copyright (c) 2014, Clippings Ltd. Developed by Ivan Kerin

Under BSD-3-Clause license, read LICENSE file.
