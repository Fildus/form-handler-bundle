<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\FormHandlerBundle\Builder;

/**
 * Class HandlerConfigBuilder
 * @package TBoileau\FormHandlerBundle\Builder
 * @author Thomas Boileau <t-boileau@email.com>
 */
class HandlerConfigBuilder implements HandlerConfigBuilderInterface
{
    /**
     * Form type class name
     *
     * @var string|null
     */
    private $formTypeClass;

    /**
     * {@inheritdoc}
     */
    public function getFormTypeClass(): ?string
    {
        return $this->formTypeClass;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormTypeClass(?string $formTypeClass): void
    {
        $this->formTypeClass = $formTypeClass;
    }
}
