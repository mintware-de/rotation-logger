<?php

declare(strict_types=1);

namespace MintwareDe\RotationLogger\Rotation;

interface FileRotatorInterface
{
    /**
     * Checks if the file requires a rotation
     *
     * @param string $filename The log file.
     *
     * @return bool True if the files needs a rotation.
     */
    public function needsRotation(string $filename): bool;

    /**
     * Performs the rotation.
     * @param string $filename The logfile to rotate.
     */
    public function rotate(string $filename): void;
}
