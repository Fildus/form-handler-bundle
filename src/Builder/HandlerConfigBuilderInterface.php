<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\FormHandlerBundle\Builder;

/**
 * Interface HandlerConfigBuilderInterface
 * @package TBoileau\FormHandlerBundle\Builder
 * @author Thomas Boileau <t-boileau@email.com>
 */
interface HandlerConfigBuilderInterface
{

    /**
     * Retrieve form type class name
     *
     * @return string|null
     */
    public function getFormTypeClass(): ?string;

    /**
     * Set form type class name
     *
     * @param string|null $formTypeClass
     */
    public function setFormTypeClass(?string $formTypeClass): void;
}
