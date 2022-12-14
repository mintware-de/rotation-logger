# Rotation Logger
A PSR-3 Logger implementation that supports rollover.

## Installation
```bash
composer require mintware-de/rotation-logger
```

## Usage
```php
<?php

use MintwareDe\RotationLogger\Rotation\RotateOptions;
use MintwareDe\RotationLogger\RotationLogger;

require_once __DIR__ . '/vendor/autoload.php';

$logger = RotationLogger::create(
    '/var/log/my-log-file',
    new RotateOptions(
        size: 10 * 1_024 * 1_024, // 10 MB; size in bytes
        rotate: 1, // Create 1 rollover file
    ),
);

$logger->debug('foo');
```

You can also use the default constructor of the `RotationLogger` class if you need to overwrite other classes.
Take a look in the `RotationLogger::create()` method if you need guidance.
