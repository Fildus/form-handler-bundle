<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Tests\Model;

/**
 * Class Bar
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Tests\Model
 * @author Thomas Boileau <t-boileau@email.com>
 */
class Bar
{
    /**
     * @var string
     */
    private $name = "";

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
