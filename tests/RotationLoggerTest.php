<?php

declare(strict_types=1);

namespace MintwareDe\Tests\RotationLogger;

use MintwareDe\RotationLogger\Formatting\LogLineFormatterInterface;
use MintwareDe\RotationLogger\Output\FileWriterInterface;
use MintwareDe\RotationLogger\Rotation\RotateOptions;
use MintwareDe\RotationLogger\RotationLogger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;

/**
 * Rotation logik:
 * - pro tag?
 * - maximale größe (Bytes)
 * - maximale größe (Zeilen)
 * - maximale Anzahl an logs
 * - logs älter als X Tage löschen?
 *
 * Schreib Logik:
 * - Wenn max. Dateigröße erreicht wurde:
 *   - Hard cut oder Zeile fertig schreiben
 */
class RotationLoggerTest extends TestCase
{
    private RotationLogger $logger;
    private MockObject&LogLineFormatterInterface $mockLogLineFormatter;
    private MockObject&FileWriterInterface $mockFileWriter;

    protected function setUp(): void
    {
        $this->mockLogLineFormatter = self::createMock(LogLineFormatterInterface::class);
        $this->mockFileWriter = self::createMock(FileWriterInterface::class);
        $this->logger = new RotationLogger($this->mockFileWriter, $this->mockLogLineFormatter);
    }

    public function testInheritance(): void
    {
        self::assertInstanceOf(AbstractLogger::class, $this->logger);
    }

    public function testCreate(): void
    {
        $options = new RotateOptions(
            size: 5 * 1024 * 1024,
            rotate: 3,
        );

        $logger = RotationLogger::create(__DIR__ . '/../tmp/log.txt', $options);
        self::assertInstanceOf(RotationLogger::class, $logger);
    }

    public function testShouldLog(): void
    {
        $this->mockLogLineFormatter
            ->expects(self::once())
            ->method('format')
            ->with(self::isInstanceOf(\DateTimeInterface::class), 'debug', 'hello', ['foo' => 'bar'])
            ->willReturn('formatted-line');

        $this->mockFileWriter
            ->expects(self::once())
            ->method('write')
            ->with("formatted-line\n");

        $this->logger->debug('hello', ['foo' => 'bar']);
    }
}
