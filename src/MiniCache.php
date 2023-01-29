<?php

declare(strict_types=1);

namespace Koriym\MiniCache;

use FilesystemIterator;
use Koriym\MiniCache\Exception\DirectoryNotWritableException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Contracts\Cache\CacheInterface;

use function assert;
use function file_exists;
use function file_put_contents;
use function hash;
use function is_string;
use function is_writable;
use function mkdir;
use function pathinfo;
use function rename;
use function rmdir;
use function substr;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;

use const DIRECTORY_SEPARATOR;
use const PATHINFO_DIRNAME;

final class MiniCache implements CacheInterface
{
    private const EXT = 'php';

    private string $tmpDir;

    public function __construct(?string $tmpDir = null)
    {
        $this->tmpDir =  $tmpDir ?? sys_get_temp_dir();
    }

    /**
     * @psalm-param callable():scalar $callback
     *
     * @return scalar
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function get(string $key, callable $callback, ?float $beta = null, ?array &$metadata = null): mixed
    {
        unset($beta, $metadata);
        $filename = $this->getFilename($key);
        if (! file_exists($filename)) {
            $value = $callback();
            $this->writeFile($this->getFilename($key), $value);

            return $value;
        }

        /** @var scalar $value */
        $value = require $filename;

        return $value;
    }

    public function delete(string $key): bool
    {
        $filename = $this->getFilename($key);

        return @unlink($filename) || ! file_exists($filename);
    }

    public function flush(): bool
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->tmpDir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($iterator as $name => $file) {
            assert($file instanceof SplFileInfo);

            if ($file->isDir()) {
                assert(is_string($name));
                @rmdir($name);
                continue;
            }

            if ($file->getExtension() === self::EXT) {
                assert(is_string($name));
                @unlink($name);
            }
        }

        return true;
    }

    private function getFilename(string $id): string
    {
        $hash = hash('crc32', $id);

        $dir = $this->tmpDir
            . DIRECTORY_SEPARATOR
            . substr($hash, 0, 2);
        if (! is_writable($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir
            . DIRECTORY_SEPARATOR
            . $hash
            . '.'
            . self::EXT;
    }

    /** @param scalar $value */
    private function writeFile(string $filename, $value): void
    {
        $filepath = pathinfo($filename, PATHINFO_DIRNAME);
        if (! is_writable($filepath)) {
            // @codeCoverageIgnoreStart
            throw new DirectoryNotWritableException($filepath);
            // @codeCoverageIgnoreEnd
        }

        $tmpFile = (string) tempnam($filepath, 'swap');
        if (is_string($value)) {
            $value = "'{$value}'";
        }

        $content = '<?php return ' . $value . ';';
        if (file_put_contents($tmpFile, $content) !== false) {
            if (@rename($tmpFile, $filename)) {
                return;
            }

            // @codeCoverageIgnoreStart
            @unlink($tmpFile);
        }

        throw new DirectoryNotWritableException($filepath);
        // @codeCoverageIgnoreEnd
    }
}
