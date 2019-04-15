<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use TBoileau\Bundle\FormHandlerBundle\Manager\HandlerManagerInterface;

/**
 * Class FormHandlerPass
 *
 * @package TBoileau\Bundle\FormHandlerBundle\DependencyInjection\Compiler
 * @author Thomas Boileau <t-boileau@email.com>
 */
class FormHandlerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition("t_boileau.form_handler.manager_factory");

        $definition->addMethodCall('setServiceLocator', [$this->processManager($container)]);

        $definition = $container->getDefinition("t_boileau.form_handler.config");

        $definition->replaceArgument(0, $this->processConfig($container));
    }

    /**
     * @param ContainerBuilder $container
     * @return Reference
     */
    private function processManager(ContainerBuilder $container): Reference
    {
        /** @var Reference[] $servicesMap */
        $servicesMap =  [];

        $taggedServices = $container->findTaggedServiceIds("t_boileau.form_handler", true);

        /**
         * @var string $serviceId
         * @var array $taggedServiceId
         */
        foreach ($taggedServices as $serviceId => $taggedServiceId) {
            $servicesMap[$container->getDefinition($serviceId)->getClass()] = new Reference($serviceId);
        }

        $servicesMap[HandlerManagerInterface::class] = new Reference(HandlerManagerInterface::class);

        return ServiceLocatorTagPass::register($container, $servicesMap);
    }

    /**
     * @param ContainerBuilder $container
     * @return Reference
     */
    private function processConfig(ContainerBuilder $container): Reference
    {
        /** @var Reference[] $servicesMap */
        $servicesMap =  [];

        $taggedServices = $container->findTaggedServiceIds("t_boileau.data_mapper", true);

        /**
         * @var string $serviceId
         * @var array $taggedServiceId
         */
        foreach ($taggedServices as $serviceId => $taggedServiceId) {
            $servicesMap[$container->getDefinition($serviceId)->getClass()] = new Reference($serviceId);
        }

        return ServiceLocatorTagPass::register($container, $servicesMap);
    }
}
