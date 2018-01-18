<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SettingBundle\DependencyInjection\Compiler;

use Mindy\Bundle\SettingBundle\Settings\Registry;
use Mindy\Bundle\SettingBundle\Settings\SettingsInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SettingsPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(Registry::class)) {
            return;
        }
        $definition = $container->getDefinition(Registry::class);

        $container
            ->registerForAutoconfiguration(SettingsInterface::class)
            ->setPublic(true)
            ->addTag('settings');

        foreach ($container->findTaggedServiceIds('settings') as $id => $params) {
            $definition->addMethodCall('add', [new Reference($id)]);
        }
    }
}
