<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Handler;

use TBoileau\Bundle\FormHandlerBundle\Config\HandlerConfigInterface;
use TBoileau\Bundle\FormHandlerBundle\Manager\HandlerManagerInterface;

/**
 * Interface HandlerInterface
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Handler
 * @author Thomas Boileau <t-boileau@email.com>
 */
interface HandlerInterface
{
    /**
     * Add your logic when the form is submitted and valid.
     *
     * @param HandlerManagerInterface $manager
     */
    public function process(HandlerManagerInterface $manager): void;

    /**
     * Configure your handler
     *
     * @param HandlerConfigInterface $config
     */
    public function configure(HandlerConfigInterface $config): void;
}
