<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\FormHandlerBundle\Config;

use TBoileau\FormHandlerBundle\Exception\ClassNotFoundException;
use TBoileau\FormHandlerBundle\Exception\ExistingOptionException;

/**
 * Interface HandlerConfigBuilderInterface
 * @package TBoileau\FormHandlerBundle\Config
 * @author Thomas Boileau <t-boileau@email.com>
 */
interface HandlerConfigInterface
{
    /**
     * Set form type class name you want to handle
     *
     * @throws ClassNotFoundException
     * @param string|null $formTypeClass
     * @return HandlerConfigInterface
     */
    public function use(string $formTypeClass): self;


    /**
     * Add a new form option
     *
     * @throws ExistingOptionException
     * @param string $key
     * @param mixed $value
     * @return HandlerConfigInterface
     */
    public function with(string $key, $value): self;

    /**
     * Retrieve form type class name
     *
     * @return string
     */
    public function getFormType(): string;


    /**
     * Retrieve form options
     *
     * @return array
     */
    public function getOptions(): array;
}
