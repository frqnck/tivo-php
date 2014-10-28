<?php

namespace JimLind\TiVo\Tests\Utilities;

use JimLind\TiVo\Utilities;

/**
 * Test the TiVo\Utilities\Log class.
 */
class LogTest extends \PHPUnit_Framework_TestCase
{
    protected $logger = null;

    /**
     * Setup the PHPUnit test.
     */
    public function setup()
    {
        $this->logger = $this->getMockBuilder('\Psr\Log\LoggerInterface')
                             ->disableOriginalConstructor()
                             ->getMock();
    }

    /**
     * Test logging with a logger.
     */
    public function testLoggedWarn()
    {
        $this->logger->expects($this->exactly(1))
                     ->method('warning')
                     ->with($this->equalTo('warning text'));

        Utilities\Log::warn('warning text', $this->logger);
    }

    /**
     * Test logging with nothing.
     */
    public function testNullWarn()
    {
        $this->logger->expects($this->never())
                     ->method('warning');

        Utilities\Log::warn('warning text', null);
    }

}
