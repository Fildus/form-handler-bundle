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
use TBoileau\FormHandlerBundle\Error\HandlerError;
use TBoileau\FormHandlerBundle\Exception\FormNotCreatedException;
use TBoileau\FormHandlerBundle\Handler\HandlerInterface;

/**
 * Class HandlerManager
 * @package TBoileau\FormHandlerBundle\Manager
 * @author Thomas Boileau <t-boileau@email.com>
 */
class HandlerManager implements HandlerManagerInterface
{
    /**
     * @var HandlerInterface
     */
    private $handler;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var HandlerConfigInterface
     */
    private $config;

    /**
     * @var mixed|null
     */
    private $data;

    /**
     * @var bool
     */
    private $valid = false;

    /**
     * @var HandlerError[]
     */
    private $errors = [];

    /**
     * HandlerManager constructor.
     * @param HandlerInterface $handler
     * @param FormFactoryInterface $formFactory
     * @param HandlerConfigInterface $config
     * @param null $data
     */
    public function __construct(
        HandlerInterface $handler,
        FormFactoryInterface $formFactory,
        HandlerConfigInterface $config,
        $data = null
    ) {
        $this->handler = $handler;
        $this->formFactory = $formFactory;
        $this->config = $config;
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function createForm(): void
    {
        $this->form = $this->formFactory->create(
            $this->config->getFormType(),
            $this->data,
            $this->config->getOptions()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createView(): FormView
    {
        if ($this->form === null) {
            throw new FormNotCreatedException('Form has not been created');
        }

        return $this->form->createView();
    }

    /**
     * {@inheritdoc}
     */
    public function addError(string $message): void
    {
        $this->errors[] = new HandlerError($message);

        $this->valid = false;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request): HandlerManagerInterface
    {
        $this->handler->configure($this->config);

        $this->createForm();

        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->valid = true;
            $this->handler->process($this);
        }

        return $this;
    }
}