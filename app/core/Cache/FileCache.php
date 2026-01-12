<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Cache;

/**
 * File-based cache implementation
 * Implements SRP principle
 */
class FileCache implements CacheInterface
{
    /**
     * @var string
     */
    private string $cacheDir;

    /**
     * @var int Default TTL in seconds (1 hour)
     */
    private int $defaultTtl = 3600;

    /**
     * @param string|null $cacheDir
     */
    public function __construct(string $cacheDir = null)
    {
        $this->cacheDir = $cacheDir ?? __DIR__ . '/../../../storage/cache';
        
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Get cache file path
     *
     * @param string $key
     * @return string
     */
    private function getCachePath(string $key): string
    {
        $hash = md5($key);
        $subDir = substr($hash, 0, 2);
        $dir = $this->cacheDir . '/' . $subDir;
        
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        return $dir . '/' . $hash . '.cache';
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $path = $this->getCachePath($key);
        
        if (!file_exists($path)) {
            return $default;
        }

        $data = unserialize(file_get_contents($path));
        
        if ($data === false || !isset($data['expires_at']) || !isset($data['value'])) {
            $this->delete($key);
            return $default;
        }

        if ($data['expires_at'] < time()) {
            $this->delete($key);
            return $default;
        }

        return $data['value'];
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        $path = $this->getCachePath($key);
        $ttl = $ttl ?? $this->defaultTtl;
        
        $data = [
            'value' => $value,
            'expires_at' => time() + $ttl,
            'created_at' => time()
        ];

        return file_put_contents($path, serialize($data)) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        $path = $this->getCachePath($key);
        
        if (!file_exists($path)) {
            return false;
        }

        $data = unserialize(file_get_contents($path));
        
        if ($data === false || !isset($data['expires_at'])) {
            return false;
        }

        if ($data['expires_at'] < time()) {
            $this->delete($key);
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $key): bool
    {
        $path = $this->getCachePath($key);
        
        if (file_exists($path)) {
            return unlink($path);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): bool
    {
        if (!is_dir($this->cacheDir)) {
            return true;
        }
        return $this->deleteDirectory($this->cacheDir);
    }

    /**
     * Delete directory recursively
     *
     * @param string $dir
     * @return bool
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = @scandir($dir);
        if ($files === false) {
            return false;
        }
        
        $files = array_diff($files, ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                @unlink($path);
            }
        }

        return @rmdir($dir);
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiple(array $keys): array
    {
        $results = [];
        
        foreach ($keys as $key) {
            $results[$key] = $this->get($key);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function setMultiple(array $values, ?int $ttl = null): bool
    {
        $success = true;
        
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMultiple(array $keys): bool
    {
        $success = true;
        
        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                $success = false;
            }
        }

        return $success;
    }
}

