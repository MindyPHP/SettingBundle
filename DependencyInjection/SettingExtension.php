<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SettingBundle\DependencyInjection;

use Mindy\Bundle\SettingBundle\Settings\SettingsManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SettingExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $configPath = sprintf(
            "%s/%s",
            $container->getParameter('kernel.project_dir'),
            ltrim($config['path'], '/')
        );

        $definition = $container->getDefinition(SettingsManager::class);
        $definition->setArgument(0, $configPath);

        if (is_file($configPath)) {
            $userLoader = new YamlFileLoader($container, new FileLocator(dirname($configPath)));
            $userLoader->load(basename($configPath));
        }
    }
}
