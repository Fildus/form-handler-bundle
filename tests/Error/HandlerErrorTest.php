<?php

/*
 * (c) Thomas Boileau <t-boileau@email.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TBoileau\Bundle\FormHandlerBundle\Tests\Error;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use TBoileau\Bundle\FormHandlerBundle\Error\HandlerError;

/**
 * Class HandlerErrorTest
 * @package TBoileau\Bundle\FormHandlerBundle\Tests\Error
 * @author Thomas Boileau <t-boileau@email.com>
 */
class HandlerErrorTest extends TestCase
{
    /**
     * @var HandlerError
     */
    private $error;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->error = new HandlerError('error');
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->error = null;
    }

    /**
     * @see HandlerError::getMessage()
     */
    public function testSuccessfulRetrieveMessage()
    {
        $this->assertEquals(
            'error',
            $this->error->getMessage()
        );
    }

    /**
     * @see HandlerError::getMessage()
     */
    public function testFailedRetrieveMessage()
    {
        $this->assertNotEquals(
            'fail',
            $this->error->getMessage()
        );
    }

    /**
     * @see HandlerError::serialize()
     */
    public function testSuccessfulSerialization()
    {
        $this->assertEquals(
            serialize(["error"]),
            $this->error->serialize()
        );
    }

    /**
     * @see HandlerError::serialize()
     */
    public function testFailedSerialization()
    {
        $this->assertNotEquals(
            serialize(["fail"]),
            $this->error->serialize()
        );
    }

    /**
     * @see HandlerError::unserialize()
     */
    public function testSuccessfulUnerialization()
    {
        $this->error->unserialize(serialize(["error"]));

        $this->assertEquals(
            'error',
            $this->error->getMessage()
        );
    }

    /**
     * @see HandlerError::unserialize()
     */
    public function testFailedUnserialization()
    {
        $this->error->unserialize(serialize(["error"]));

        $this->assertNotEquals(
            'fail',
            $this->error->getMessage()
        );
    }
}
