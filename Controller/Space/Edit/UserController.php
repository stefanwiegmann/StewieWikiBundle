<?php

namespace Stewie\WikiBundle\Controller\Space\Edit;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Stewie\UserBundle\Form\Type\User\AddUserType;

/**
  * @IsGranted("ROLE_WIKI_SPACE_EDIT")
  */

class UserController extends AbstractController
{
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
    * @Route("/space/edit/user/{slug}/{page}", defaults={"page": 1}
    *     , requirements={"page": "\d+"}, name="stewie_wiki_space_edit_user")
    */
    public function details($slug, $page, Request $request)
    {
      //get space
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository('StewieWikiBundle:Space');
      $space = $repo->findOneBySlug($slug);

      // create form
      $form = $this->createForm(AddUserType::class);

      // handle form
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

          $userRepo = $em->getRepository('StewieUserBundle:User');
          $user = $userRepo->findOneById($form->get('userAutoId')->getData());

          // add user
          $space->addUser($user);
          $em->persist($space);
          $em->flush();

          $this->addFlash(
              'success',
              'User was added!'
              );

          return $this->redirectToRoute('stewie_wiki_space_edit_user', array('slug' => $slug));
        }

      //get data and paginate
      $pagination = $this->paginator->paginate(
            // $this->getQuery($space), /* query NOT result */
            $space->getUser(), /* query NOT result */
            $request->query->getInt('page', $page)/*page number*/,
            // 10/*limit per page*/
            $this->getParameter('stewie_wiki.max_rows')/*limit per page*/
        );
        $pagination->setTemplate('@StewieWiki/default/pagination.html.twig');

      return $this->render('@StewieWiki/space/edit/user.html.twig', [
          'space' => $space,
          'userList' => $pagination,
          'page' => $page,
          'form' => $form->createView(),
      ]);
    }

    // public function getQuery($space){
    //
    //     $repository = $this->getDoctrine()
    //       ->getRepository('StewieWikiBundle:Space');
    //
    //     $query = $repository->createQueryBuilder('u')
    //       ->andWhere(':groups MEMBER OF u.groups')
    //       ->setParameter('groups', $group)
    //       ->orderBy('u.id', 'ASC');
    //
    //       return $query
    //         ->getQuery();
    //
    // }
}
