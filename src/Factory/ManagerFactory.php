<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\FormHandlerBundle\Factory;

use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Form\FormFactoryInterface;
use TBoileau\FormHandlerBundle\Config\HandlerConfigInterface;
use TBoileau\FormHandlerBundle\Manager\HandlerManager;
use TBoileau\FormHandlerBundle\Manager\HandlerManagerInterface;

/**
 * Class ManagerFactory
 * @package TBoileau\FormHandlerBundle\Factory
 * @author Thomas Boileau <t-boileau@email.com>
 */
class ManagerFactory implements ManagerFactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ServiceLocator
     */
    private $serviceLocator;

    /**
     * @var HandlerConfigInterface
     */
    private $handlerConfig;

    /**
     * ManagerFactory constructor.
     * @param FormFactoryInterface $formFactory
     * @param ServiceLocator $serviceLocator
     * @param HandlerConfigInterface $handlerConfig
     */
    public function __construct(FormFactoryInterface $formFactory, ServiceLocator $serviceLocator, HandlerConfigInterface $handlerConfig)
    {
        $this->formFactory = $formFactory;
        $this->serviceLocator = $serviceLocator;
        $this->handlerConfig = $handlerConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $handler, $data = null, array $options = []): HandlerManagerInterface
    {
        return new HandlerManager(
            $this->serviceLocator->get($handler),
            $this->formFactory,
            $this->handlerConfig
        );
    }
}
