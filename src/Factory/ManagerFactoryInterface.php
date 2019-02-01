<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\FormHandlerBundle\Factory;

use TBoileau\FormHandlerBundle\Manager\HandlerManagerInterface;

/**
 * Interface ManagerFactoryInterface
 * @package TBoileau\FormHandlerBundle\Factory
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
}
