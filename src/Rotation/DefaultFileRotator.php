<?php

declare(strict_types=1);

namespace MintwareDe\RotationLogger\Rotation;

class DefaultFileRotator implements FileRotatorInterface
{
    public function __construct(
        private readonly RotateOptions $options
    ) {
    }

    /** @inheritDoc */
    public function needsRotation(string $filename): bool
    {
        if ($this->options->size !== null) {
            $fp = fopen($filename, "rb");
            // @codeCoverageIgnoreStart
            if ($fp === false) {
                throw new \Exception('Could not open ' . $filename);
            }
            // @codeCoverageIgnoreEnd
            fseek($fp, 0, SEEK_END);
            $size = ftell($fp);
            fclose($fp);
            if ($size !== false && $size >= $this->options->size) {
                return true;
            }
        }
        return false;
    }

    /** @inheritDoc */
    public function rotate(string $filename): void
    {
        $rollOverFiles = $this->options->rotate;
        $this->rotateExistingFiles($rollOverFiles, $filename);
        if ($rollOverFiles > 0) {
            rename($filename, $filename . '.1');
        } else {
            unlink($filename);
        }
        touch($filename);
    }

    private function rotateExistingFiles(int $rollOverFiles, string $filename): void
    {
        for ($i = $rollOverFiles; $i > 0; $i--) {
            $rollOverFile = $filename . '.' . $i;
            $nextRollOverFile = $filename . '.' . ($i + 1);
            if (!is_file($rollOverFile)) {
                continue;
            }

            if ($i < $rollOverFiles) {
                rename($rollOverFile, $nextRollOverFile);
            } else {
                unlink($rollOverFile);
            }
        }
    }
}
