<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Tests\DependencyInjection\Compiler;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use TBoileau\Bundle\FormHandlerBundle\DataMapper\DataMapperInterface;
use TBoileau\Bundle\FormHandlerBundle\DependencyInjection\Compiler\FormHandlerPass;
use TBoileau\Bundle\FormHandlerBundle\DependencyInjection\Compiler\HandlerPass;
use TBoileau\Bundle\FormHandlerBundle\DependencyInjection\TBoileauFormHandlerExtension;
use TBoileau\Bundle\FormHandlerBundle\Handler\HandlerInterface;
use TBoileau\Bundle\FormHandlerBundle\Tests\DataMapper\FooMapper;
use TBoileau\Bundle\FormHandlerBundle\Tests\Handler\FooHandler;

/**
 * Class HandlerPassTest
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Tests\DependencyInjection\Compiler
 * @author Thomas Boileau <t-boileau@email.com>
 */
class FormHandlerPassTest extends TestCase
{
    /**
     * @var ContainerBuilder|null
     */
    private $container;

    /**
     * @var HandlerPass|null
     */
    private $formHandlerPass;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->formHandlerPass = new FormHandlerPass();

        $this->container = new ContainerBuilder(new ParameterBag());

        (new TBoileauFormHandlerExtension())->load([], $this->container);

        $handlerDefinition = new Definition(FooHandler::class);
        $handlerDefinition->setTags(["t_boileau.form_handler" => []]);

        $this->container->setDefinition(FooHandler::class, $handlerDefinition);

        $mapperDefinition = new Definition(FooMapper::class);
        $mapperDefinition->setTags(["t_boileau.data_mapper" => []]);

        $this->container->setDefinition(FooMapper::class, $mapperDefinition);

        $this->formHandlerPass->process($this->container);
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->formHandlerPass = null;
        $this->container = null;
    }

    public function testSuccessfulProcess()
    {
        $this->assertInstanceOf(HandlerInterface::class, $this->container->get(FooHandler::class));
        $this->assertInstanceOf(DataMapperInterface::class, $this->container->get(FooMapper::class));
    }
}
