<?php

declare(strict_types=1);

namespace MintwareDe\RotationLogger;

use MintwareDe\RotationLogger\Formatting\DefaultLogLineLineFormatter;
use MintwareDe\RotationLogger\Formatting\LogLineFormatterInterface;
use MintwareDe\RotationLogger\Output\FileWriter;
use MintwareDe\RotationLogger\Output\FileWriterInterface;
use MintwareDe\RotationLogger\Rotation\DefaultFileRotator;
use MintwareDe\RotationLogger\Rotation\RotateOptions;
use Psr\Log\AbstractLogger;

class RotationLogger extends AbstractLogger
{
    private readonly LogLineFormatterInterface $formatter;

    public function __construct(
        private readonly FileWriterInterface $fileWriter,
        ?LogLineFormatterInterface $formatter = null,
    ) {
        $this->formatter = $formatter ?? new DefaultLogLineLineFormatter();
    }

    public static function create(string $filename, RotateOptions $options): RotationLogger
    {
        $fileWriter = new FileWriter($filename, new DefaultFileRotator($options));
        return new RotationLogger($fileWriter);
    }

    /**
     * @inheritDoc
     * @param mixed[] $context
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $line = $this->formatter->format(new \DateTime(), strval($level), $message, $context);
        $this->fileWriter->write($line . PHP_EOL);
    }
}
