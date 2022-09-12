<?php

namespace Stewie\WikiBundle\Controller\Page\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
// use Stewie\UserBundle\Form\Type\User\DetailType;
use Stewie\WikiBundle\Entity\Space;
use Stewie\WikiBundle\Entity\Page;

/**
  * @IsGranted("ROLE_WIKI_PAGE_VIEW")
  */

class PageController extends AbstractController
{
    /**
    * @Route("/page/view/space/{slug}", name="stewie_wiki_page_view_root")
    */
    public function root($slug, Request $request)
    {
      //get user
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository(Space::Class);
      $space = $repo->findOneBySlug($slug);

      return $this->redirectToRoute('stewie_wiki_page_view_page', ['slug' => $space->getPage()->first()->getSlug()]);

    }

    /**
    * @Route("/page/view/page/{slug}", name="stewie_wiki_page_view_page")
    */
    public function page($slug, Request $request)
    {
      //get user
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository(Page::Class);
      $page = $repo->findOneBySlug($slug);

      return $this->render('@StewieWiki/page/view/page.html.twig', [
          'page' => $page,
          'space' => $page->getSpace(),
      ]);
    }
}
