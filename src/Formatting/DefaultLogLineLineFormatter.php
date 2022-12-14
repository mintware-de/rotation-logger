<?php

declare(strict_types=1);

namespace MintwareDe\RotationLogger\Formatting;

class DefaultLogLineLineFormatter implements LogLineFormatterInterface
{
    /** @inheritDoc */
    public function format(
        \DateTimeInterface $date,
        string $level,
        \Stringable|string $message,
        array $context = [],
    ): string {
        $interpolated = $this->interpolate(strval($message), $context);
        return sprintf('[%s] %s: %s', $date->format('c'), $level, $interpolated);
    }

    /**
     * @param mixed[] $context
     */
    private function interpolate(string $message, array $context = []): string
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
