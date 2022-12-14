<?php

declare(strict_types=1);

namespace MintwareDe\RotationLogger\Formatting;

interface LogLineFormatterInterface
{
    /**
     * Formats a line for the log.
     *
     * @param \DateTimeInterface $date    The date when this log
     * @param string             $level   The log level
     * @param \Stringable|string $message The log message
     * @param mixed[]            $context The context
     *
     * @return string The formatted line
     */
    public function format(
        \DateTimeInterface $date,
        string $level,
        \Stringable|string $message,
        array $context = [],
    ): string;
}
