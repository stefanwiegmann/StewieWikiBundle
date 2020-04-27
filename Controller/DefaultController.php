<?php

namespace Stewie\WikiBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
    * @Route("/wiki", name="stefanwiegmann_wiki_home")
    */
    public function home()
    {
      return $this->render('@StefanwiegmannWiki/default/index.html.twig', [
          'headline' => 'wiki headline',
          'content' => 'wiki content',
      ]);
    }
}
