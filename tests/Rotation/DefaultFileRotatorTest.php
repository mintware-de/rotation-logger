<?php

declare(strict_types=1);

namespace MintwareDe\Tests\RotationLogger\Rotation;

use MintwareDe\RotationLogger\Rotation\DefaultFileRotator;
use MintwareDe\RotationLogger\Rotation\FileRotatorInterface;
use MintwareDe\RotationLogger\Rotation\RotateOptions;
use PHPUnit\Framework\TestCase;

class DefaultFileRotatorTest extends TestCase
{
    public function testExists(): void
    {
        $rotator = new DefaultFileRotator(new RotateOptions());
        self::assertInstanceOf(FileRotatorInterface::class, $rotator);
    }

    public function testNeedsRotationFileSize(): void
    {
        $rotator = new DefaultFileRotator(new RotateOptions(size: 10));
        self::assertFalse($rotator->needsRotation(__DIR__ . '/../assets/short.log'));
        self::assertTrue($rotator->needsRotation(__DIR__ . '/../assets/long.log'));
    }

    public function testRotateWithoutRolloverFiles(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'tests');
        if ($tmpFile === false) {
            $this->fail('Could not create tmp file');
        }
        file_put_contents($tmpFile, "test\ntest\ntest\ntest\ntest\ntest\n");
        self::assertFileDoesNotExist($tmpFile . '.1');
        $rotator = new DefaultFileRotator(new RotateOptions(size: 4));
        $rotator->rotate($tmpFile);
        self::assertFileDoesNotExist($tmpFile . '.1');
    }

    public function testRotateWithRolloverFiles(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'tests');
        if ($tmpFile === false) {
            $this->fail('Could not create tmp file');
        }
        file_put_contents($tmpFile, "test\ntest\ntest\ntest\ntest\ntest\n");
        self::assertFileDoesNotExist($tmpFile . '.1');
        $rotator = new DefaultFileRotator(new RotateOptions(size: 4, rotate: 1));
        $rotator->rotate($tmpFile);
        self::assertFileExists($tmpFile . '.1');
    }

    public function testRotateWithExistingRolloverFiles(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'tests');
        if ($tmpFile === false) {
            $this->fail('Could not create tmp file');
        }
        file_put_contents($tmpFile, "test2");
        file_put_contents($tmpFile . '.1', "test1");
        self::assertFileExists($tmpFile . '.1');
        self::assertFileDoesNotExist($tmpFile . '.2');
        $rotator = new DefaultFileRotator(new RotateOptions(size: 4, rotate: 2));
        $rotator->rotate($tmpFile);
        self::assertEquals('test1', file_get_contents($tmpFile . '.2'));

        file_put_contents($tmpFile, "test3");
        $rotator->rotate($tmpFile);
        file_put_contents($tmpFile, "test4");
        $rotator->rotate($tmpFile);
        self::assertFileExists($tmpFile . '.2');
        self::assertFileDoesNotExist($tmpFile . '.4');
    }
}
