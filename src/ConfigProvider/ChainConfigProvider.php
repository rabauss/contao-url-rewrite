<?php

declare(strict_types=1);

/*
 * UrlRewrite Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2021, terminal42 gmbh
 * @author     terminal42 <https://terminal42.ch>
 * @license    MIT
 */

namespace Terminal42\UrlRewriteBundle\ConfigProvider;

use Terminal42\UrlRewriteBundle\RewriteConfigInterface;

class ChainConfigProvider implements ConfigProviderInterface
{
    /**
     * @var array
     */
    private $providers = [];

    /**
     * Add the config provider.
     */
    public function addProvider(ConfigProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $id): ?RewriteConfigInterface
    {
        [$class, $id] = explode(':', $id);

        /** @var ConfigProviderInterface $provider */
        foreach ($this->providers as $provider) {
            if ($class === $this->getProviderIdentifier($provider) && null !== ($config = $provider->find($id))) {
                return $config;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $configs = [];

        /** @var ConfigProviderInterface $provider */
        foreach ($this->providers as $provider) {
            $providerConfigs = $provider->findAll();

            /** @var RewriteConfigInterface $config */
            foreach ($providerConfigs as $config) {
                $config->setIdentifier(static::getConfigIdentifier($this->getProviderIdentifier($provider), $config->getIdentifier()));
            }

            $configs = array_merge($configs, $providerConfigs);
        }

        return $configs;
    }

    /**
     * Get the config identifier.
     */
    public static function getConfigIdentifier(string $providerIdentifier, string $configIdentifier): string
    {
        return $providerIdentifier.':'.$configIdentifier;
    }

    /**
     * Get the provider identifier.
     */
    private function getProviderIdentifier(ConfigProviderInterface $provider): string
    {
        return \get_class($provider);
    }
}
