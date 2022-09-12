<?php

namespace Stewie\WikiBundle\Controller\Page;

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
use Stewie\WikiBundle\Entity\Page;

/**
  * @IsGranted("ROLE_WIKI_PAGE_DELETE")
  */

class DeleteController extends AbstractController
{
  /**
  * @Route("/page/delete/{slug}", name="stewie_wiki_page_delete")
  */
  public function deleteAction($slug, Request $request, TranslatorInterface $translator)
  {
    //get user
    $em = $this->container->get('doctrine')->getManager();
    $repo = $em->getRepository(Page::Class);
    $page = $repo->findOneBySlug($slug);

    // create form
    // $form = $this->createForm(DeleteType::class, $space);
    $form = $this->createFormBuilder($page)

            ->add('submit', SubmitType::class, array(
                'label' => 'label.delete',
                'translation_domain' => 'StewieWikiBundle',
                'attr'=> array('class'=>'btn-danger'),
            ))

            ->getForm();

    // handle form
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $page = $form->getData();

        // delete page
        $em->remove($page);
        $em->flush();

        $this->addFlash(
            'success',
            'Page was deleted!'
            );

        return $this->redirectToRoute('stewie_wiki_space_edit_page', ['slug' => $page->getSpace()->getSlug()]);
      }

    return $this->render('@StewieUser/card/dangerForm.html.twig', [
        'title' => $translator->trans('title.page.delete', [
          '%subject%' => $page->getTitle()
          ], 'StewieWikiBundle'),
        'text' => $translator->trans('text.page.delete', [], 'StewieWikiBundle'),
        'page' => $page,
        'form' => $form->createView(),
    ]);

  }
}
