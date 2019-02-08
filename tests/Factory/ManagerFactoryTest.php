<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Tests\Factory;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ServiceLocator;
use TBoileau\Bundle\FormHandlerBundle\Factory\ManagerFactory;
use TBoileau\Bundle\FormHandlerBundle\Factory\ManagerFactoryInterface;
use TBoileau\Bundle\FormHandlerBundle\Handler\HandlerInterface;
use TBoileau\Bundle\FormHandlerBundle\Manager\HandlerManagerInterface;

/**
 * Class ManagerFactoryTest
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Tests\Factory
 * @author Thomas Boileau <t-boileau@email.com>
 */
class ManagerFactoryTest extends TestCase
{
    /**
     * @var ManagerFactoryInterface
     */
    private $managerFactory;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $handlerManager = $this->createMock(HandlerManagerInterface::class);

        $handler = $this->createMock(HandlerInterface::class);

        $serviceLocator = $this->createMock(ServiceLocator::class);
        $serviceLocator->method("get")->willReturn($handler);

        $this->managerFactory = new ManagerFactory($handlerManager);

        $this->managerFactory->setServiceLocator($serviceLocator);
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->managerFactory = null;
    }


    public function testCreate()
    {
        $this->assertInstanceOf(HandlerManagerInterface::class, $this->managerFactory->create(""));
    }
}
