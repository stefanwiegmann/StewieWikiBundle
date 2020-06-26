<?php

namespace Stewie\WikiBundle\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PathFinder extends AbstractController
{
    public function getBundlePath(){

        $filesystem = new Filesystem();
        $path = 'Path not found!';

        if($filesystem->exists('lib/stewie/wiki-bundle')){

            $path = 'lib/stewie/wiki-bundle/';

        }elseif($filesystem->exists('vendor/stewie/wiki-bundle')){

            $path = 'vendor/stewie/wiki-bundle/';

        }

        return $path;
    }
}
