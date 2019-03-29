<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Config;

use TBoileau\Bundle\FormHandlerBundle\DataMapper\DataMapperInterface;

/**
 * Interface HandlerConfigBuilderInterface
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Config
 * @author Thomas Boileau <t-boileau@email.com>
 */
interface HandlerConfigInterface
{
    /**
     * Set data mapper class you want to transform handle data to model data
     *
     * @param string $dataMapperClass
     * @return HandlerConfigInterface
     */
    public function mappedBy(string $dataMapperClass): self;

    /**
     * Set form type class name you want to handle
     *
     * @param string $formTypeClass
     * @return HandlerConfigInterface
     */
    public function use(string $formTypeClass): self;

    /**
     * Add a new form option
     *
     * @param string $key
     * @param mixed $value
     * @return HandlerConfigInterface
     */
    public function with(string $key, $value): self;

    /**
     * Retrieve form type class name
     *
     * @return string|null
     */
    public function getFormType(): ?string;

    /**
     * Retrieve data mapper service
     *
     * @return DataMapperInterface|null
     */
    public function getDataMapper(): ?DataMapperInterface;

    /**
     * Retrieve form options
     *
     * @return array
     */
    public function getOptions(): array;
}
