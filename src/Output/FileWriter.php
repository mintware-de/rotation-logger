<?php

declare(strict_types=1);

namespace MintwareDe\RotationLogger\Output;

use MintwareDe\RotationLogger\Rotation\DefaultFileRotator;
use MintwareDe\RotationLogger\Rotation\FileRotatorInterface;
use MintwareDe\RotationLogger\Rotation\RotateOptions;

class FileWriter implements FileWriterInterface
{
    /** @var resource */
    private mixed $handle;
    private FileRotatorInterface $fileRotator;

    public function __construct(
        private readonly string $filename,
        ?FileRotatorInterface $fileRotator = null,
    ) {
        if (!is_file($this->filename)) {
            $dir = dirname($this->filename);
            // @codeCoverageIgnoreStart
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            // @codeCoverageIgnoreEnd
        }

        $this->fileRotator = $fileRotator ?? new DefaultFileRotator(new RotateOptions());
        $this->createHandleForWriting();
    }

    public function __destruct()
    {
        fclose($this->handle);
    }

    public function write(string $data): void
    {
        $this->rotateIfNecessary();
        fwrite($this->handle, $data);
        fflush($this->handle);
    }

    private function rotateIfNecessary(): void
    {
        if ($this->fileRotator->needsRotation($this->filename)) {
            fclose($this->handle);
            $this->fileRotator->rotate($this->filename);
            $this->createHandleForWriting();
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function createHandleForWriting(): void
    {
        $handle = fopen($this->filename, 'a+');
        // @codeCoverageIgnoreStart
        if (!is_resource($handle)) {
            throw new \Exception("Could not open file for writing.");
        }
        // @codeCoverageIgnoreEnd

        $this->handle = $handle;
    }
}
