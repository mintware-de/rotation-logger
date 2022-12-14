<?php

declare(strict_types=1);

namespace MintwareDe\Tests\RotationLogger\Formatting;

use MintwareDe\RotationLogger\Formatting\DefaultLogLineLineFormatter;
use MintwareDe\RotationLogger\Formatting\LogLineFormatterInterface;
use PHPUnit\Framework\TestCase;

class DefaultLogLineFormatterTest extends TestCase
{
    private DefaultLogLineLineFormatter $formatter;

    protected function setUp(): void
    {
        $this->formatter = new DefaultLogLineLineFormatter();
    }

    public function testExists(): void
    {
        self::assertInstanceOf(LogLineFormatterInterface::class, $this->formatter);
    }

    public function testFormatSimple(): void
    {
        $expected = '[2022-12-14T06:39:00+00:00] error: test';
        $date = new \DateTime('2022-12-14T06:39:00Z');
        self::assertEquals($expected, $this->formatter->format($date, 'error', 'test'));
    }

    public function testFormatWithInterpolation(): void
    {
        $expected = '[2022-12-14T06:39:00+00:00] error: exception message ex-msg';
        $date = new \DateTime('2022-12-14T06:39:00Z');
        self::assertEquals($expected, $this->formatter->format($date, 'error', 'exception message {message}', [
            'message' => 'ex-msg',
        ]));
    }
}
