<?php

declare(strict_types=1);

namespace Pextras\NamespacedCache;

use Psr\SimpleCache\CacheInterface;

class NamespacedCache implements CacheInterface
{
    private const SEPARATOR = '|';

    /** @var CacheInterface */
    private $cache;

    /** @var string */
    private $namespace;

    public function __construct(CacheInterface $cache, string $namespace)
    {
        $this->cache = $cache;
        $this->namespace = $namespace;
    }

    private function prefixKey(string $key): string
    {
        return self::SEPARATOR . $this->namespace . self::SEPARATOR . $key;
    }

    private function prefixKeys(iterable $generator): iterable
    {
        foreach ($generator as $key) {
            yield $this->prefixKey($key);
        }
    }

    private function prefixMultiple(iterable $generator): iterable
    {
        foreach ($generator as $key => $value) {
            yield $this->prefixKey($key) => $value;
        }
    }

    private function removePrefixKeys(iterable $generator): iterable
    {
        foreach ($generator as $key => $value) {
            yield ltrim($key, self::SEPARATOR . $this->namespace . self::SEPARATOR) => $value;
        }
    }

    public function get($key, $default = null)
    {
        return $this->cache->get($this->prefixKey($key), $default);
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->cache->set($this->prefixKey($key), $value, $ttl);
    }

    public function delete($key)
    {
        return $this->cache->delete($this->prefixKey($key));
    }

    public function clear()
    {
        return $this->cache->delete(self::SEPARATOR . $this->namespace);
    }

    public function getMultiple($keys, $default = null)
    {
        return $this->removePrefixKeys($this->cache->getMultiple($this->prefixKeys($keys), $default));
    }

    public function setMultiple($values, $ttl = null)
    {
        return $this->cache->setMultiple($this->prefixMultiple($values), $ttl);
    }

    public function deleteMultiple($keys)
    {
        return $this->cache->deleteMultiple($this->prefixKeys($keys));
    }

    public function has($key)
    {
        return $this->cache->has($this->prefixKey($key));
    }
}
