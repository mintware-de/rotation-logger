<?php

declare(strict_types=1);

namespace MintwareDe\Tests\RotationLogger\Output;

use MintwareDe\RotationLogger\Output\FileWriter;
use MintwareDe\RotationLogger\Rotation\FileRotatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileWriterTest extends TestCase
{
    private FileWriter $fileWriter;
    private string $logFile;
    private FileRotatorInterface&MockObject $mockFileRotator;

    protected function setUp(): void
    {
        $this->logFile = __DIR__ . '/../../tmp/log.txt';
        if (is_file($this->logFile)) {
            unlink($this->logFile);
        }
        $this->mockFileRotator = self::createMock(FileRotatorInterface::class);
        $this->fileWriter = new FileWriter($this->logFile, $this->mockFileRotator);
    }

    public function testShouldCreateTheFile(): void
    {
        self::assertFileExists($this->logFile);
    }

    public function testWrite(): void
    {
        $this->fileWriter->write("test\n");
        self::assertEquals("test\n", file_get_contents($this->logFile));
    }

    public function testWriteShouldAppend(): void
    {
        $this->fileWriter->write("test\n");
        $this->fileWriter = new FileWriter($this->logFile);
        $this->fileWriter->write("test 2\n");

        self::assertEquals("test\ntest 2\n", file_get_contents($this->logFile));
    }

    public function testRotate(): void
    {
        $this->mockFileRotator
            ->expects(self::exactly(2))
            ->method('needsRotation')
            ->with($this->logFile)
            ->willReturnOnConsecutiveCalls(true, false);

        $this->mockFileRotator
            ->expects(self::once())
            ->method('rotate')
            ->with($this->logFile);
        $this->fileWriter->write("test\n");
        $this->fileWriter->write("test\n");
    }
}
