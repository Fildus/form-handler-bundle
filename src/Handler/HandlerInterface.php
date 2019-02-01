<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\FormHandlerBundle\Handler;

use TBoileau\FormHandlerBundle\Config\HandlerConfigInterface;

/**
 * Interface HandlerInterface
 * @package TBoileau\FormHandlerBundle\Handler
 * @author Thomas Boileau <t-boileau@email.com>
 */
interface HandlerInterface
{
    /**
     * Add your logic when the form is submitted and valid.
     */
    public function process(): void;

    /**
     * Configure your handler
     *
     * @param HandlerConfigInterface $config
     */
    public function configure(HandlerConfigInterface $config): void;
}
