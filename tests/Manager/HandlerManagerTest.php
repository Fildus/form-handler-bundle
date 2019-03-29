<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Tests\Manager;

use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\Request;
use TBoileau\Bundle\FormHandlerBundle\Config\HandlerConfigInterface;
use TBoileau\Bundle\FormHandlerBundle\Error\HandlerError;
use TBoileau\Bundle\FormHandlerBundle\Exception\FormNotCreatedException;
use TBoileau\Bundle\FormHandlerBundle\Handler\HandlerInterface;
use TBoileau\Bundle\FormHandlerBundle\Manager\HandlerManager;
use TBoileau\Bundle\FormHandlerBundle\Manager\HandlerManagerInterface;
use TBoileau\Bundle\FormHandlerBundle\Tests\DataMapper\FooMapper;
use TBoileau\Bundle\FormHandlerBundle\Tests\Model\Foo;
use TBoileau\Bundle\FormHandlerBundle\Tests\Form\FooType;

/**
 * Class HandlerManagerTest
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Tests\Manager
 * @author Thomas Boileau <t-boileau@email.com>
 */
class HandlerManagerTest extends TypeTestCase
{
    /**
     * @var HandlerManagerInterface
     */
    private $manager;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $dataMapper = new FooMapper();

        $config = $this->createMock(HandlerConfigInterface::class);
        $config->method("getFormType")->willReturn(FooType::class);
        $config->method("getDataMapper")->willReturn($dataMapper);

        $handler = $this->createMock(HandlerInterface::class);

        $this->manager = new HandlerManager($this->factory, $config);
        $this->manager->setHandler($handler);
    }

    /**
     * @inheritdoc
     */
    protected function getExtensions()
    {
        return [
            new HttpFoundationExtension()
        ];
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->manager = null;
    }

    public function testSuccessfulAddError()
    {
        $errorsToCompare = [new HandlerError("error")];

        $this->manager->addError("error");

        $this->assertFalse($this->manager->isValid());

        $this->assertEquals($errorsToCompare, $this->manager->getErrors());
    }

    public function testSuccessfulHandle()
    {
        $formData = [
            "name" => "value"
        ];

        $fooToCompare = new Foo();

        $this->manager->setData($fooToCompare);

        $this->assertEquals($fooToCompare, $this->manager->getData());

        $request = Request::create('/', Request::METHOD_POST, [
            'foo' => $formData
        ]);

        $foo = new Foo();
        $foo->setBar("value");

        $this->assertEquals($this->manager, $this->manager->handle($request));

        $this->assertInstanceOf(FormInterface::class, $this->manager->getForm());

        $this->assertInstanceOf(FormView::class, $this->manager->createView());

        $this->assertEquals($foo, $fooToCompare);

        $this->assertTrue($this->manager->isValid());
    }

    public function testFailedCreateForm()
    {
        $this->manager = new HandlerManager($this->factory, $this->createMock(HandlerConfigInterface::class));
        $this->expectException(FormNotCreatedException::class);
        $this->manager->createForm();
    }

    public function testFailedCreateView()
    {
        $this->manager = new HandlerManager($this->factory, $this->createMock(HandlerConfigInterface::class));
        $this->expectException(FormNotCreatedException::class);
        $this->manager->createView();
    }
}
