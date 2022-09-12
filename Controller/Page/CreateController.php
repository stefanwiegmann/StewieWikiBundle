<?php

namespace Stewie\WikiBundle\Controller\Page;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stewie\WikiBundle\Form\Type\Page\CreateType;
use Stewie\WikiBundle\Entity\Page;
use Stewie\WikiBundle\Entity\Space;
// use Stewie\WikiBundle\Service\AvatarGenerator;
// use Symfony\Component\HttpFoundation\File\File;

/**
  * @IsGranted("ROLE_WIKI_PAGE_CREATE")
  */

class CreateController extends AbstractController
{
    /**
    * @Route("/page/create/{slug}", name="stewie_wiki_page_create")
    */
    public function create($slug, Request $request)
    {
      //get space
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository(Space::Class);
      $space = $repo->findOneBySlug($slug);

      //create page
      $page = new Page;
      $page->setSpace($space);

      // create form
      $form = $this->createForm(CreateType::class, $page);

      // handle form
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $page = $form->getData();

          // save page
          // $em = $this->container->get('doctrine')->getManager();
          $em->persist($page);
          $em->flush();

          return $this->redirectToRoute('stewie_wiki_space_edit_page', ['slug' => $page->getSpace()->getSlug()]);
        }

      return $this->render('@StewieWiki/page/create.html.twig', [
          'page' => $page,
          'space' => $space,
          'form' => $form->createView(),
      ]);
    }
}
