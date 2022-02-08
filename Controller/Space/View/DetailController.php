<?php

namespace Stewie\WikiBundle\Controller\Space\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
// use Stewie\UserBundle\Form\Type\User\DetailType;

/**
  * @IsGranted("ROLE_WIKI_SPACE_VIEW")
  */

class DetailController extends AbstractController
{
    /**
    * @Route("/space/view/detail/{slug}", name="stewie_wiki_space_view_detail")
    */
    public function details($slug, Request $request)
    {
      //get user
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository('StewieWikiBundle:Space');
      $space = $repo->findOneBySlug($slug);

      return $this->render('@StewieWiki/space/view/detail.html.twig', [
          'space' => $space,
      ]);
    }
}
