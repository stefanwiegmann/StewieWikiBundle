<?php

namespace Stewie\WikiBundle\Controller\Page\Edit;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stewie\WikiBundle\Form\Type\Page\EditType;
// use Stewie\WikiBundle\Service\AvatarGenerator;
// use Symfony\Component\HttpFoundation\File\File;
use Stewie\WikiBundle\Entity\Page;

/**
  * @IsGranted("ROLE_WIKI_PAGE_EDIT")
  */

class PageController extends AbstractController
{
    /**
    * @Route("/page/edit/{slug}", name="stewie_wiki_page_edit")
    */
    public function edit($slug, Request $request)
    {
      //get user
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository(Page::Class);
      $page = $repo->findOneBySlug($slug);

      // create form
      $form = $this->createForm(EditType::class, $page);

      // handle form
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $page = $form->getData();

          // save page
          $em->persist($page);
          $em->flush();

          return $this->redirectToRoute('stewie_wiki_page_view_page', ['slug' => $page->getSlug()]);
        }

      return $this->render('@StewieWiki/page/edit/edit.html.twig', [
          'page' => $page,
          'space' => $page->getSpace(),
          'form' => $form->createView(),
      ]);
    }
}
