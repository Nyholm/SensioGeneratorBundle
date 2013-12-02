<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensio\Bundle\GeneratorBundle\Tests\Command;

use Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class GeneratorCommandTest extends GenerateCommandTest
{
    public function testGetSkeletonDirs()
    {
        $container=$this->getContainer();
        $kernel=$container->get('kernel');

        /*
         * Get some paths to possible skeleton dirs
         */
        $skeletonResources=array(
            '/path/Acme/DemoBundle/Resources/skeleton',
            //the path to this bundle's skeletons
            dirname(dirname(__DIR__)).'/Resources/skeleton',
            '/path/ZMan/GeneratorBundle/Resources/skeleton',
        );

        $kernel
            ->expects($this->once())
            ->method('locateResource')
            ->will($this->returnValue($skeletonResources))
        ;
        $container->set('kernel', $kernel);

        $generator = new GeneratorCommandDummy();
        $generator->setContainer($container);

        $dirs=$generator->getSkeletonDirs();

        $prioritizedSkeletonResources=array(
            '/path/Acme/DemoBundle/Resources/skeleton',
            '/path/ZMan/GeneratorBundle/Resources/skeleton',
            //This should be the last one
            dirname(dirname(__DIR__)).'/Resources/skeleton',
        );

        $this->assertEquals($prioritizedSkeletonResources, array_slice($dirs, 0, 3));

    }
}

class GeneratorCommandDummy extends GeneratorCommand
{
    public function getSkeletonDirs(BundleInterface $bundle = null)
    {
        return parent::getSkeletonDirs($bundle);
    }

    public function configure()
    {
        $this->setName('GeneratorCommandDummy');
    }

    public function createGenerator()
    {
        return null;
    }
}