<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Error;

/**
 * Class HandlerError
 *
 * Wraps error in handler
 *
 * @package TBoileau\Bundle\FormHandlerBundle\Error
 * @author Thomas Boileau <t-boileau@email.com>
 */
class HandlerError implements \Serializable
{
    /**
     * @var string
     */
    private $message;

    /**
     * HandlerError constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Retrieve handler error
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return serialize([
            $this->message
        ]);
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        list($this->message) = unserialize($serialized, ['allowed_classes' => false]);
    }
}
