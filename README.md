RokuWSPHP - Roku Web Service SDK for PHP 5.3+
==============================

Roku SDK for communicating with the Roku Web Service API: http://sdkdocs.roku.com/display/sdkdoc/Web+Service+API

Usage
-----

Install the latest version with `composer require raymauge/rokuwsphp`

```php
<?php

use RokuWS\RokuWS;

$ws = new RokuWS(<apiKey>);
$rokuResponse = $ws->validateTransaction(<transactionId>);
$responseArray = $rokuResponse->getParsedResponse();
$expDate = $responseArray['expirationDate'];
```

License
-------

RokuWSPHP  is licensed under the MIT license.