<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Factory;

use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Form\FormFactoryInterface;
use TBoileau\Bundle\FormHandlerBundle\Config\HandlerConfigInterface;
use TBoileau\Bundle\FormHandlerBundle\Manager\HandlerManagerInterface;

/**
 * Class ManagerFactory
 *
 * Creates a new handler manager
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Factory
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
     * @var HandlerManagerInterface
     */
    private $handlerManager;

    /**
     * ManagerFactory constructor.
     * @param FormFactoryInterface $formFactory
     * @param HandlerConfigInterface $handlerConfig
     * @param HandlerManagerInterface $handlerManager
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        HandlerConfigInterface $handlerConfig,
        HandlerManagerInterface $handlerManager
    ) {
        $this->formFactory = $formFactory;
        $this->handlerConfig = $handlerConfig;
        $this->handlerManager = $handlerManager;
    }

    /**
     * {@inheritdoc}
     */
    public function setServiceLocator(ServiceLocator $serviceLocator): void
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $handler, $data = null, array $options = []): HandlerManagerInterface
    {
        $this->handlerManager->setHandler($this->serviceLocator->get($handler));
        $this->handlerManager->setData($data);

        return $this->handlerManager;
    }
}
