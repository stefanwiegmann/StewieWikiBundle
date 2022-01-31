<?php

namespace Stewie\WikiBundle\Controller\Space\Edit;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
  * @IsGranted("ROLE_WIKI_SPACE_EDIT")
  */

class RemoveController extends AbstractController
{
  /**
  * @Route("/space/remove/user/{slug}/{user}", name="stewie_wiki_space_remove_user")
  */
  public function userAction($slug, $user, Request $request, TranslatorInterface $translator)
  {
    //get space
    $em = $this->container->get('doctrine')->getManager();
    $spaceRepo = $em->getRepository('StewieWikiBundle:Space');
    $spaceObject = $spaceRepo->findOneBySlug($slug);

    //get user
    $em = $this->container->get('doctrine')->getManager();
    $userRepo = $em->getRepository('StewieUserBundle:User');
    $userObject = $userRepo->findOneById($user);

    // create form
    $form = $this->createFormBuilder($spaceObject)
            ->add('submit', SubmitType::class, array('label' => 'label.remove',
            'translation_domain' => 'StewieWikiBundle',
            'attr'=> array('class'=>'btn-danger'),))
            ->getForm();

    // handle form
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // remove user from space
        $spaceObject->removeUser($userObject);

        // save user
        $em->persist($spaceObject);
        $em->flush();

        return $this->redirectToRoute('stewie_wiki_space_edit_user', array('slug' => $slug));
      }

    return $this->render('@StewieUser/card/dangerForm.html.twig', [
        'text' => $translator->trans('text.space.remove.user', [
          '%subject%' => $userObject->getUsername(),
          '%object%' => $spaceObject->getName()
          ], 'StewieWikiBundle'),
        'title' => $translator->trans('header.space.remove.user', [], 'StewieWikiBundle'),
        'form' => $form->createView(),
    ]);

  }

  // /**
  // * @Route("/user/space/remove/member", name="stewie_user_space_remove_member")
  // */
  // public function memberAction(Request $request)
  // {
  //
  //   if($request->isXmlHttpRequest()) {
  //
  //     $spaceId   = $request->request->get('spaceId');
  //     $userId   = $request->request->get('userId');
  //
  //     $spaceRepo = $this->getDoctrine()
  //       ->getRepository('StewieWikiBundle:Space');
  //     $space = $spaceRepo->findOneById($spaceId);
  //
  //     $userRepo = $this->getDoctrine()
  //       ->getRepository('StewieWikiBundle:User');
  //     $user = $userRepo->findOneById($userId);
  //
  //     $em = $this->getDoctrine()->getManager();
  //     $user->removeSpace($space);
  //     $em->persist($user);
  //     $em->flush();
  //
  //     // refresh roles for that user
  //     $userRepo->refreshRoles($user);
  //
  //     $this->addFlash(
  //       'success',
  //       $user->getUsername().' was removed from Space '.$space->getName().'!'
  //       );
  //
  //     $response = array("code" => 100, "success" => true, "spaceId" => $spaceId, "userId" => $userId);
  //     return new Response(json_encode($response));
  //
  //   } else {
  //     return $this->redirectToRoute('stewie_user_space_list');
  //   }
  // }

}
