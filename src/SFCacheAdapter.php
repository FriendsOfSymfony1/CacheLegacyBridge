<?php

declare(strict_types=1);

namespace Symfony1\LegacyBridge\Cache;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;

abstract class SFCacheAdapter extends \sfCache
{
    /**
     * @var AdapterInterface
     */
    protected $cacheAdapter;

    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->cacheAdapter = $this->createAdapter();
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @throws InvalidArgumentException
     *
     * @return string|mixed
     */
    public function get($key, $default = null)
    {
        $item = $this->cacheAdapter->getItem(self::getCleanKey($key));
        if (!$item->isHit()) {
            return  $default;
        }

        return $item->get();
    }

    /**
     * @param string $key
     *
     * @throws InvalidArgumentException
     */
    public function has($key): bool
    {
        return $this->cacheAdapter->hasItem(self::getCleanKey($key));
    }

    /**
     * @param string   $key
     * @param string   $data
     * @param int|null $lifetime
     *
     * @throws InvalidArgumentException
     */
    public function set($key, $data, $lifetime = null): bool
    {
        $item = $this->cacheAdapter->getItem(self::getCleanKey($key))
            ->expiresAfter($this->getLifetime($lifetime))
            ->set($data)
        ;

        return $this->cacheAdapter->save($item);
    }

    /**
     * @param string $key
     */
    public function remove($key): bool
    {
        return $this->cacheAdapter->delete(self::getCleanKey($key));
    }

    /**
     * @param int $mode
     */
    public function clean($mode = self::ALL): bool
    {
        if (self::ALL === $mode) {
            return $this->cacheAdapter->clear();
        }

        if (self::OLD === $mode) {
            // @TODO: to be implemented
            throw new \RuntimeException(__METHOD__.'($mode=OLD) not implemented');
        }

        return false;
    }

    /**
     * @param string $key
     */
    public function getLastModified($key): int
    {
        throw new \RuntimeException(__METHOD__.'() not implemented');
    }

    /**
     * @param string $key
     */
    public function getTimeout($key): int
    {
        throw new \RuntimeException(__METHOD__.'() not implemented');
    }

    /**
     * @param string $pattern
     */
    public function removePattern($pattern): bool
    {
        throw new \RuntimeException(__METHOD__.'() not implemented');
    }

    abstract protected function createAdapter(): AdapterInterface;

    protected function getClearPrefix(): string
    {
        return substr($this->options['prefix'] ?? '', 0, -1) ?? '';
    }

    private static function getCleanKey(string $key): string
    {
        return str_replace(['{', '}', '(', ')', '/', '\'', '@', ':'], '', $key);
    }
}
