<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\FormHandlerBundle\Manager;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use TBoileau\FormHandlerBundle\Config\HandlerConfigInterface;
use TBoileau\FormHandlerBundle\Exception\FormNotCreatedException;
use TBoileau\FormHandlerBundle\Handler\HandlerInterface;

/**
 * Interface HandlerManager
 * @package TBoileau\FormHandlerBundle\Manager
 * @author Thomas Boileau <t-boileau@email.com>
 */
interface HandlerManagerInterface
{
    /**
     * Create form
     *
     * @throws FormNotCreatedException
     */
    public function createForm(): void;

    /**
     * Add new handler error
     *
     * @param string $message
     */
    public function addError(string $message): void;

    /**
     * A shortcut to create form view
     *
     * @throws FormNotCreatedException
     * @return FormView
     */
    public function createView(): FormView;

    /**
     * Check if the form has been handled correctly
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Handle form with current request
     *
     * @param Request $request
     * @return HandlerManagerInterface
     */
    public function handle(Request $request): self;
}