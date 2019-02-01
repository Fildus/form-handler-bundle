<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\FormHandlerBundle\Config;

use Symfony\Component\Form\Exception\AlreadySubmittedException;
use TBoileau\FormHandlerBundle\Exception\ClassNotFoundException;

/**
 * Class HandlerConfigBuilder
 * @package TBoileau\FormHandlerBundle\Config
 * @author Thomas Boileau <t-boileau@email.com>
 */
class HandlerConfig implements HandlerConfigInterface
{
    /**
     * Form type class name
     *
     * @var string|null
     */
    private $formTypeClass;

    /**
     * Form type options
     *
     * @var mixed[]
     */
    private $options = [];

    /**
     * {@inheritdoc}
     */
    public function use(string $formTypeClass): HandlerConfigInterface
    {
        if (!class_exists($formTypeClass)) {
            throw new ClassNotFoundException('You need to pass an existing class');
        }

        $this->formTypeClass = $formTypeClass;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function with(string $key, $value): HandlerConfigInterface
    {
        if (isset($this->options[$key])) {
            throw new AlreadySubmittedException('You cannot add an option that already exists');
        }

        $this->options[$key] = $value;

        return $this;
    }
}
