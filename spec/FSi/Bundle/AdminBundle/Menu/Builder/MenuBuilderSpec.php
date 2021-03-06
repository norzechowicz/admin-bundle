<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\Builder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MenuBuilderSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    function let($dispatcher)
    {
        $this->beConstructedWith($dispatcher, 'fsi_admin.menu.tools');
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function it_should_emit_proper_event($dispatcher)
    {
        $dispatcher->dispatch('fsi_admin.menu.tools', Argument::allOf(
            Argument::type('FSi\Bundle\AdminBundle\Event\MenuEvent')
        ))->shouldBeCalled();

        $this->buildMenu()->shouldReturnAnInstanceOf('FSi\Bundle\AdminBundle\Menu\Item\Item');
    }
}
