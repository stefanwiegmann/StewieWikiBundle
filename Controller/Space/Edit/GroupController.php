<?php

namespace Stewie\WikiBundle\Controller\Space\Edit;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stewie\WikiBundle\Form\Type\Space\GroupType;

/**
  * @IsGranted("ROLE_WIKI_SPACE_EDIT")
  */

class GroupController extends AbstractController
{
    /**
    * @Route("/space/edit/group/{slug}", name="stewie_wiki_space_edit_group")
    */
    public function groups($slug, Request $request)
    {
      //get user
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository('StewieWikiBundle:Space');
      $space = $repo->findOneBySlug($slug);

      // create form
      $form = $this->createForm(GroupType::class, $space);

      // handle form
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $space = $form->getData();

          // update groups
          // $groupRepo = $em->getRepository('StewieUserBundle:Group');
          // $groupRepo->updateUser($user);

          // save space
          $em->persist($space);
          $em->flush();

          // update affected user roles
          // $repo->refreshRoles($user);

          return $this->redirectToRoute('stewie_wiki_space_edit_group', ['slug' => $space->getSlug()]);
        }

      return $this->render('@StewieWiki/space/edit/group.html.twig', [
          'space' => $space,
          'form' => $form->createView(),
      ]);
    }
}
