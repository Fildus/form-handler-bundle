<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\DataMapper;

/**
 * Interface DataMapperInterface
 *
 * @package TBoileau\Bundle\FormHandlerBundle\DataMapper
 * @author Thomas Boileau <t-boileau@email.com>
 */
interface DataMapperInterface
{
    /**
     * Map handle data to model data
     *
     * @param mixed|null $data
     * @return mixed
     */
    public function map($data);

    /**
     * Map model data to handle data
     *
     * @param mixed $modelData
     * @param mixed|null $handleData
     * @return mixed
     */
    public function reverseMap($modelData, $handleData);
}
