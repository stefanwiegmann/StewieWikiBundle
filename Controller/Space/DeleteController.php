<?php

namespace Stewie\WikiBundle\Controller\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
// use Stewie\WikiBundle\Form\Type\Group\DeleteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Stewie\WikiBundle\Entity\Space;

/**
  * @IsGranted("ROLE_WIKI_SPACE_DELETE")
  */

class DeleteController extends AbstractController
{
  /**
  * @Route("/space/delete/{slug}", name="stewie_wiki_space_delete")
  */
  public function deleteAction($slug, Request $request, TranslatorInterface $translator)
  {
    //get user
    $em = $this->container->get('doctrine')->getManager();
    $repo = $em->getRepository(Space::Class);
    $space = $repo->findOneBySlug($slug);

    // create form
    // $form = $this->createForm(DeleteType::class, $space);
    $form = $this->createFormBuilder($space)

            ->add('submit', SubmitType::class, array(
                'label' => 'label.delete',
                'translation_domain' => 'StewieWikiBundle',
                'attr'=> array('class'=>'btn-danger'),
            ))

            ->getForm();

    // handle form
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $space = $form->getData();

        // save user
        $em->remove($space);
        $em->flush();

        $this->addFlash(
            'success',
            'Space was deleted!'
            );

        return $this->redirectToRoute('stewie_wiki_space_list');
      }

    return $this->render('@StewieUser/card/dangerForm.html.twig', [
        'title' => $translator->trans('title.space.delete', [
          '%subject%' => $space->getName()
          ], 'StewieWikiBundle'),
        'text' => $translator->trans('text.space.delete', [], 'StewieWikiBundle'),
        'space' => $space,
        'form' => $form->createView(),
    ]);

  }
}
