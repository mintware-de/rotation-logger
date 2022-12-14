<?php

declare(strict_types=1);

namespace MintwareDe\Tests\RotationLogger\Rotation;

use MintwareDe\RotationLogger\Rotation\RotateOptions;
use PHPUnit\Framework\TestCase;

class RotateOptionsTest extends TestCase
{
    public function testConstructorDefaultOptions(): void
    {
        $options = new RotateOptions();
        self::assertNull($options->size);
        self::assertEquals(0, $options->rotate);
    }

    public function testConstructorOverwriteOptions(): void
    {
        $options = new RotateOptions(
            size: 10,
        );
        self::assertEquals(10, $options->size);
    }
}
