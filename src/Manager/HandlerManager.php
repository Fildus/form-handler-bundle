<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Manager;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use TBoileau\Bundle\FormHandlerBundle\Config\HandlerConfigInterface;
use TBoileau\Bundle\FormHandlerBundle\Exception\FormNotCreatedException;
use TBoileau\Bundle\FormHandlerBundle\Exception\MappingFailedException;
use TBoileau\Bundle\FormHandlerBundle\Handler\HandlerInterface;

/**
 * Class HandlerManager
 *
 * Manage an handler. First, we create the form with the form factory service,
 * then we handle it with the request, and finally test his validity before process the handler
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Manager
 * @author Thomas Boileau <t-boileau@email.com>
 */
class HandlerManager implements HandlerManagerInterface
{
    /**
     * @var HandlerInterface
     */
    protected $handler;

    /**
     * @var FormInterface|null
     */
    protected $form;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var HandlerConfigInterface
     */
    protected $config;

    /**
     * @var mixed|null
     */
    protected $handleData;

    /**
     * @var mixed|null
     */
    protected $modelData;

    /**
     * @var bool
     */
    protected $valid = true;

    /**
     * HandlerManager constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param HandlerConfigInterface $config
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        HandlerConfigInterface $config
    ) {
        $this->formFactory = $formFactory;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function setHandler(HandlerInterface $handler): void
    {
        $this->handler = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function createForm(): void
    {
        if ($this->config->getFormType() === null) {
            throw new FormNotCreatedException('Form type not found');
        }

        $this->form = $this->formFactory->create(
            $this->config->getFormType(),
            $this->modelData,
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
        $this->form->addError(new FormError($message));
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return $this->form->isSubmitted() && $this->form->isValid();
    }

    /**
     * {@inheritdoc}
     */
    public function handle(?Request $request): HandlerManagerInterface
    {
        $this->handler->configure($this->config);

        $this->modelData = $this->handleData;

        if ($this->config->getDataMapper() !== null) {
            $this->modelData = $this->config->getDataMapper()->map($this->handleData);
        }

        $this->createForm();

        $this->form->handleRequest($request);

        if ($this->isValid() && $this->config->getDataMapper()) {
            try {
                $this->handleData = $this->config->getDataMapper()->reverseMap($this->modelData, $this->handleData);
            } catch (MappingFailedException $exception) {
                $this->addError($exception->getMessage());
            }
        }

        if ($this->isValid()) {
            $this->handler->process($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->handleData;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data): void
    {
        $this->handleData = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(): ?FormInterface
    {
        return $this->form;
    }
}
