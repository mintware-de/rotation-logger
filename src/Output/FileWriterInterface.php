<?php

declare(strict_types=1);

namespace MintwareDe\RotationLogger\Output;

interface FileWriterInterface
{
    public function write(string $data): void;
}
