<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Tests\Model;

/**
 * Class Foo
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Tests\Model
 * @author Thomas Boileau <t-boileau@email.com>
 */
class Foo
{
    /**
     * @var string
     */
    private $bar = "";

    /**
     * @return string
     */
    public function getBar(): string
    {
        return $this->bar;
    }

    /**
     * @param string $bar
     */
    public function setBar(string $bar): void
    {
        $this->bar = $bar;
    }
}
