<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TBoileau\Bundle\FormHandlerBundle\DependencyInjection\Compiler\HandlerPass;

/**
 * Class TBoileauFormHandlerBundle
 *
 * @package TBoileau\Bundle\FormHandlerBundle
 * @author Thomas Boileau <t-boileau@email.com>
 */
class TBoileauFormHandlerBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new HandlerPass());
    }
}
