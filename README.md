# ic-package-info

[![Stable release](https://poser.pugx.org/elecena/ic-package-info/version.svg)](https://packagist.org/packages/elecena/ic-package-info)
[![Build Status](https://travis-ci.org/elecena/ic-package-info.svg?branch=master)](https://travis-ci.org/elecena/ic-package-info)
Extract IC package from a given string

## Install it

```
composer require elecena/ic-package-info
```

## Example

```php
<?php

use Elecena\Utils\PackageInfo;

$package = PackageInfo::getPackage('2N7000 N CHANNEL MOSFET, 60V, 200mA, TO-92'); // this will return 'TO-92'

// additionally, PackageInfo normalizes the packages
$package = PackageInfo::getPackage('MOSFET,N CHANNEL,600V,3.7A,SC-67'); // will return 'TO-220F'

// false will be returned when a valid package is not found
$package = PackageInfo::getPackage('foo bar-42'); // will return false
```
