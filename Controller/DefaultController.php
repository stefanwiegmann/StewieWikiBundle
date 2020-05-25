<?php

namespace Stewie\WikiBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
    * @Route("/home", name="stewie_wiki_home")
    * @Route("/", name="stewie_wiki_index")
    */
    public function home()
    {
      return $this->render('@StewieWiki/default/index.html.twig', [
          'headline' => 'wiki headline',
          'content' => 'wiki content',
      ]);
    }
}
