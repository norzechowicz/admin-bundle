<?php

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use FSi\Bundle\AdminBundle\Annotation\Element;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AdminAnnotatedElementPassSpec extends ObjectBehavior
{
    /**
     * @param \Doctrine\Common\Annotations\AnnotationReader $annotationReader
     * @param \FSi\Bundle\AdminBundle\Finder\AdminClassFinder $adminClassFinder
     */
    function let($annotationReader, $adminClassFinder)
    {
        $this->beConstructedWith($annotationReader, $adminClassFinder);

    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Doctrine\Common\Annotations\AnnotationReader $annotationReader
     * @param \FSi\Bundle\AdminBundle\Finder\AdminClassFinder $adminClassFinder
     */
    function it_registers_annotated_admin_classes_as_services($container, $annotationReader, $adminClassFinder)
    {
        $container->getParameter('kernel.bundles')->willReturn(array(
            'FSi\Bundle\AdminBundle\spec\fixtures\MyBundle',
            'FSi\Bundle\AdminBundle\FSiAdminBundle',
            'Symfony\Bundle\FrameworkBundle\FrameworkBundle'
        ));

        $baseDir = __DIR__ . '/../../../../../..';
        $adminClassFinder->findClasses(array(
            realpath($baseDir . '/spec/fixtures/Admin'),
            realpath($baseDir . '/Admin')
        ))->willReturn(array(
            'FSi\Bundle\AdminBundle\spec\fixtures\Admin\SimpleAdminElement',
            'FSi\Bundle\AdminBundle\spec\fixtures\Admin\CRUDElement'
        ));

        $annotationReader->getClassAnnotation(
            Argument::allOf(
                Argument::type('ReflectionClass'),
                Argument::which('getName', 'FSi\Bundle\AdminBundle\spec\fixtures\Admin\CRUDElement')
            ),
            'FSi\\Bundle\\AdminBundle\\Annotation\\Element'
        )->willReturn(null);

        $annotationReader->getClassAnnotation(
            Argument::allOf(
                Argument::type('ReflectionClass'),
                Argument::which('getName', 'FSi\Bundle\AdminBundle\spec\fixtures\Admin\SimpleAdminElement')
            ),
            'FSi\\Bundle\\AdminBundle\\Annotation\\Element'
        )->willReturn(new Element(array()));

        $container->addResource(Argument::allOf(
            Argument::type('Symfony\Component\Config\Resource\DirectoryResource'),
            Argument::which('getResource', realpath($baseDir . '/spec/fixtures/Admin')),
            Argument::which('getPattern', '/\.php$/')
        ))->shouldBeCalled();

        $container->addResource(Argument::allOf(
            Argument::type('Symfony\Component\Config\Resource\DirectoryResource'),
            Argument::which('getResource', realpath($baseDir . '/Admin')),
            Argument::which('getPattern', '/\.php$/')
        ))->shouldBeCalled();

        $container->addDefinitions(Argument::that(function ($definitions) {
            if (count($definitions) !== 1) {
                return false;
            }

            /** @var \Symfony\Component\DependencyInjection\Definition $definition */
            $definition = $definitions[0];
            if ($definition->getClass() !== 'FSi\Bundle\AdminBundle\spec\fixtures\Admin\SimpleAdminElement') {
                return false;
            }

            if (!$definition->hasTag('admin.element')) {
                return false;
            };

            return true;
        }))->shouldBeCalled();

        $this->process($container);
    }
}
