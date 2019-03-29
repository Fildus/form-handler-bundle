<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Tests\DataMapper;

use TBoileau\Bundle\FormHandlerBundle\DataMapper\DataMapperInterface;
use TBoileau\Bundle\FormHandlerBundle\Exception\MappingFailedException;
use TBoileau\Bundle\FormHandlerBundle\Tests\Model\Bar;
use TBoileau\Bundle\FormHandlerBundle\Tests\Model\Foo;

/**
 * Class FooMapper
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Tests\DataMapper
 * @author Thomas Boileau <t-boileau@email.com>
 */
class FooMapper implements DataMapperInterface
{
    /**
     * @param Foo $data
     * @return Bar
     */
    public function map($data)
    {
        $bar = new Bar();
        $bar->setName($data->getBar());

        return $bar;
    }

    /**
     * @param Bar $modelData
     * @param Foo $handleData
     * @return Foo
     */
    public function reverseMap($modelData, $handleData)
    {
        if ($modelData->getName() === "fail") {
            throw new MappingFailedException("Bar can't be equal to 'fail'.");
        }

        $handleData->setBar($modelData->getName());

        return $handleData;
    }
}
