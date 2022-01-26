<?php

namespace Stewie\WikiBundle\Controller\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stewie\WikiBundle\Form\Type\Space\CreateType;
use Stewie\WikiBundle\Entity\Space;
// use Stewie\UserBundle\Service\AvatarGenerator;
// use Symfony\Component\HttpFoundation\File\File;

/**
  * @IsGranted("ROLE_WIKI_SPACE_CREATE")
  */

class CreateController extends AbstractController
{
    /**
    * @Route("/space/create", name="stewie_wiki_space_create")
    */
    public function create(Request $request)
    {
      //create space
      $space = new Space;

      // create form
      $form = $this->createForm(CreateType::class, $space);

      // handle form
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $space = $form->getData();

          // // set avatar
          // $group->setAvatarName($avatarGenerator->create($group));

          // save space
          $em = $this->container->get('doctrine')->getManager();
          $em->persist($space);
          $em->flush();

          return $this->redirectToRoute('stewie_wiki_space_list');
        }

      return $this->render('@StewieWiki/space/create.html.twig', [
          'space' => $space,
          'form' => $form->createView(),
      ]);
    }
}
