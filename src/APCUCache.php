<?php

declare(strict_types=1);

namespace Symfony1\LegacyBridge\Cache;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter;

class APCUCache extends SFCacheAdapter
{
    protected function createAdapter(): AdapterInterface
    {
        return new ApcuAdapter($this->getClearPrefix(), $this->getLifetime(null));
    }
}
