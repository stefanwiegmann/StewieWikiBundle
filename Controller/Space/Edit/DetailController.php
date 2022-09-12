<?php

namespace Stewie\WikiBundle\Controller\Space\Edit;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stewie\WikiBundle\Form\Type\Space\DetailType;
// use Stewie\WikiBundle\Service\AvatarGenerator;
// use Symfony\Component\HttpFoundation\File\File;
use Stewie\WikiBundle\Entity\Space;

/**
  * @IsGranted("ROLE_WIKI_SPACE_EDIT")
  */

class DetailController extends AbstractController
{
    /**
    * @Route("/space/edit/detail/{slug}", name="stewie_wiki_space_edit_detail")
    */
    public function details($slug, Request $request)
    {
      //get user
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository(Space::Class);
      $space = $repo->findOneBySlug($slug);

      // create form
      $form = $this->createForm(DetailType::class, $space);

      // handle form
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $space = $form->getData();

          // // // set avatar if old avatar was removed
          // if(!$space->getAvatarFile()){
          //   // $avatar = new File($avatarGenerator->create($user->getUsername()));
          //   $space->setAvatarName($avatarGenerator->create($space));
          //   // $user->setAvatarSize(0);
          // }

          // save user
          $em->persist($space);
          $em->flush();

          return $this->redirectToRoute('stewie_wiki_space_list');
        }

      return $this->render('@StewieWiki/space/edit/detail.html.twig', [
          'space' => $space,
          'form' => $form->createView(),
      ]);
    }
}
