<?php

namespace App\Service;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class SettingsProvider
{
    private array $settings;

    public function __construct(
        private SettingRepository $repository,
        private CacheInterface $cache,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function all(): array
    {
        return $this->settings ??= $this->cache->get('app_settings', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $rows = $this->repository->getAll();
            $data = [];
            foreach ($rows as $row) {
                $data[$row->getKeyName()] = $row->getValue();
            }
            return $data;
        });
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function invalidate(): void
    {
        $this->cache->delete('app_settings');
    }
}
