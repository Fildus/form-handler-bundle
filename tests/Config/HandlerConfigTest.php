<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Tests\Config;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use TBoileau\Bundle\FormHandlerBundle\Config\HandlerConfig;
use TBoileau\Bundle\FormHandlerBundle\Config\HandlerConfigInterface;
use TBoileau\Bundle\FormHandlerBundle\DataMapper\DataMapperInterface;
use TBoileau\Bundle\FormHandlerBundle\Exception\ClassNotFoundException;
use TBoileau\Bundle\FormHandlerBundle\Exception\ExistingOptionException;
use TBoileau\Bundle\FormHandlerBundle\Tests\DataMapper\FooMapper;

/**
 * Class HandlerConfigTest
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Tests\Config
 * @author Thomas Boileau <t-boileau@email.com>
 */
class HandlerConfigTest extends TestCase
{
    /**
     * @var HandlerConfigInterface|null
     */
    private $config;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $serviceLocator = $this->createMock(ServiceLocator::class);
        $serviceLocator->method("get")->willReturnCallback([$this, "getService"]);

        $this->config = new HandlerConfig($serviceLocator);

        $this->config->with('existing_key', 'value');
    }

    /**
     * @param string $dataMapperClass
     * @return DataMapperInterface
     */
    public function getService(string $dataMapperClass): DataMapperInterface
    {
        return new FooMapper();
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->config = null;
    }

    public function testSuccessfulWith()
    {
        $optionsToCompare = [
            'existing_key' => 'value',
            'key' => 'value'
        ];

        $this->assertEquals($this->config, $this->config->with('key', 'value'));

        $this->assertEquals($optionsToCompare, $this->config->getOptions());

        foreach (array_keys($optionsToCompare) as $key) {
            $this->assertArrayHasKey($key, $this->config->getOptions());
        }
    }

    public function testFailedWith()
    {
        $this->expectException(ExistingOptionException::class);
        $this->config->with('existing_key', 'value');
    }

    public function testSuccessfulUse()
    {
        $formTypeToCompare = FormType::class;

        $this->assertEquals($this->config, $this->config->use(FormType::class));

        $this->assertEquals($formTypeToCompare, $this->config->getFormType());
    }

    public function testFailedUse()
    {
        $this->expectException(ClassNotFoundException::class);
        $this->config->use("fail");
    }

    public function testSuccessfulMappedBy()
    {
        $this->assertEquals($this->config, $this->config->mappedBy(FooMapper::class));

        $this->assertInstanceOf(FooMapper::class, $this->config->getDataMapper());
    }

    public function testFailedMappedBy()
    {
        $this->expectException(ClassNotFoundException::class);
        $this->config->mappedBy("fail");
    }
}
