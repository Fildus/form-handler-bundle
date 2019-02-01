<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Config;

use TBoileau\Bundle\FormHandlerBundle\Exception\ClassNotFoundException;
use TBoileau\Bundle\FormHandlerBundle\Exception\ExistingOptionException;

/**
 * Class HandlerConfig
 *
 * A configurator to set form type and form options
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Config
 * @author Thomas Boileau <t-boileau@email.com>
 */
class HandlerConfig implements HandlerConfigInterface
{
    /**
     * Form type class name
     *
     * @var string|null
     */
    private $formType;

    /**
     * Form type options
     *
     * @var mixed[]
     */
    private $options = [];

    /**
     * {@inheritdoc}
     */
    public function use(string $formType): HandlerConfigInterface
    {
        if (!class_exists($formType)) {
            throw new ClassNotFoundException('You need to pass an existing class');
        }

        $this->formType = $formType;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function with(string $key, $value): HandlerConfigInterface
    {
        if (isset($this->options[$key])) {
            throw new ExistingOptionException('You cannot add an option that already exists');
        }

        $this->options[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType(): ?string
    {
        return $this->formType;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
