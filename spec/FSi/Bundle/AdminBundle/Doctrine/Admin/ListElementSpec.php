<?php

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ListElementSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Bridge\Doctrine\ManagerRegistry $registry
     * @param \Doctrine\Common\Persistence\ObjectManager $om
     */
    function let($registry, $om)
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\Doctrine\MyListElement');
        $this->beConstructedWith(array());

        $registry->getManagerForClass('FSiDemoBundle:Entity')->willReturn($om);
        $this->setManagerRegistry($registry);
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $om
     */
    public function it_should_return_object_manager($om)
    {
        $this->getObjectManager()->shouldReturn($om);
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $om
     * @param \Doctrine\Common\Persistence\ObjectRepository $repository
     */
    public function it_should_return_object_repository($om, $repository)
    {
        $om->getRepository('FSiDemoBundle:Entity')->willReturn($repository);
        $this->getRepository()->shouldReturn($repository);
    }

    /**
     * @param \Symfony\Bridge\Doctrine\ManagerRegistry $registry
     * @param \Doctrine\Common\Persistence\ObjectManager $om
     * @param \Doctrine\Common\Persistence\ObjectRepository $repository
     * @param \Doctrine\ORM\Mapping\ClassMetadata $metadata
     */
    public function it_should_have_doctrine_data_indexer($registry, $om, $repository, $metadata)
    {
        $registry->getManagerForClass('FSi/Bundle/DemoBundle/Entity/Entity')->willReturn($om);
        $om->getRepository('FSiDemoBundle:Entity')->willReturn($repository);
        $metadata->isMappedSuperclass = false;
        $metadata->rootEntityName = 'FSi/Bundle/DemoBundle/Entity/Entity';
        $om->getClassMetadata('FSi/Bundle/DemoBundle/Entity/Entity')->willReturn($metadata);

        $repository->getClassName()->willReturn('FSi/Bundle/DemoBundle/Entity/Entity');

        $this->setManagerRegistry($registry);
        $this->getDataIndexer()->shouldReturnAnInstanceOf('FSi\Component\DataIndexer\DoctrineDataIndexer');
    }
}
