<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Tests\Handler;

use TBoileau\Bundle\FormHandlerBundle\Config\HandlerConfigInterface;
use TBoileau\Bundle\FormHandlerBundle\Handler\HandlerInterface;
use TBoileau\Bundle\FormHandlerBundle\Manager\HandlerManagerInterface;
use TBoileau\Bundle\FormHandlerBundle\Tests\Form\FooType;

/**
 * Class FooHandler
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Tests\Handler
 * @author Thomas Boileau <t-boileau@email.com>
 */
class FooHandler implements HandlerInterface
{
    /**
     * @inheritdoc
     */
    public function process(HandlerManagerInterface $manager): void
    {

    }

    /**
     * @inheritdoc
     */
    public function configure(HandlerConfigInterface $config): void
    {
        $config->use(FooType::class);
    }
}
