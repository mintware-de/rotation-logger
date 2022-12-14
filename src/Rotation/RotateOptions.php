<?php

declare(strict_types=1);

namespace MintwareDe\RotationLogger\Rotation;

class RotateOptions
{
    /**
     * @param int|null $size   The maximum log file size in bytes before the log will roll over.
     * @param int      $rotate The number of rotations before the file is deleted.
     */
    public function __construct(
        public ?int $size = null,
        public int $rotate = 0,
    ) {
    }
}
