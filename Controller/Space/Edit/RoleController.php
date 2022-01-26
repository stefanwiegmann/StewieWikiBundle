<?php

namespace Stewie\WikiBundle\Controller\Space\Edit;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stewie\WikiBundle\Form\Type\Space\RoleType;

/**
  * @IsGranted("ROLE_USER_GROUP_EDIT")
  */

class RoleController extends AbstractController
{
    /**
    * @Route("/user/group/edit/role/{slug}", name="stewie_user_group_edit_role")
    */
    public function details($slug, Request $request)
    {
      //get user
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository('StewieWikiBundle:Space');
      $group = $repo->findOneBySlug($slug);

      // create form
      $form = $this->createForm(RoleType::class, $group);

      // handle form
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $group = $form->getData();

          // save user
          $em->persist($group);
          $em->flush();

          // update affected user roles
          $repo->refreshRoles($group);

          return $this->redirectToRoute('stewie_user_group_list');
        }

      return $this->render('@StewieUser/group/edit/role.html.twig', [
          'group' => $group,
          'form' => $form->createView(),
      ]);
    }
}
