<?php

namespace Oro\Bundle\ChannelBundle\Tests\Unit\Event;

use Oro\Bundle\ChannelBundle\Entity\Channel;
use Oro\Bundle\ChannelBundle\Event\ChannelSaveEvent;

class ChannelSaveEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetter()
    {
        $channel = new Channel();
        $event   = new ChannelSaveEvent($channel);

        $this->assertSame($channel, $event->getChannel());
    }
}
