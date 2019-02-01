<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Factory;

use Symfony\Component\DependencyInjection\ServiceLocator;
use TBoileau\Bundle\FormHandlerBundle\Manager\HandlerManagerInterface;

/**
 * Interface ManagerFactoryInterface
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Factory
 * @author Thomas Boileau <t-boileau@email.com>
 */
interface ManagerFactoryInterface
{
    /**
     * Instantiates a new handler manager
     *
     * @param string $handler
     * @param null|mixed $data
     * @param array $options
     * @return HandlerManagerInterface
     */
    public function create(string $handler, $data = null, array $options = []): HandlerManagerInterface;

    /**
     * Set service locator to retrieve the handler service
     *
     * @param ServiceLocator $serviceLocator
     */
    public function setServiceLocator(ServiceLocator $serviceLocator): void;
}
