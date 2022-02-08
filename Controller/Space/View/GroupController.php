<?php

namespace Stewie\WikiBundle\Controller\Space\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
// use Stewie\UserBundle\Form\Type\User\GroupType;
use Knp\Component\Pager\PaginatorInterface;

/**
  * @IsGranted("ROLE_WIKI_SPACE_VIEW")
  */

class GroupController extends AbstractController
{
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
    * @Route("/space/view/group/{slug}/{page}", defaults={"page": 1}
    *     , requirements={"page": "\d+"}, name="stewie_wiki_space_view_group")
    */
    public function roles($slug, $page, Request $request)
    {
      //get space
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository('StewieWikiBundle:Space');
      $space = $repo->findOneBySlug($slug);

      //get data and paginate
      $pagination = $this->paginator->paginate(
      $space->getGroup(), /* query NOT result */
      $request->query->getInt('page', $page)/*page number*/,
            // 10/*limit per page*/
            $this->getParameter('stewie_wiki.max_rows')/*limit per page*/
        );
        // $pagination->setTemplate('@SWUser/User/pagination.html.twig');
        $pagination->setTemplate('@StewieWiki/default/pagination.html.twig');

      return $this->render('@StewieWiki/space/view/group.html.twig', [
          'space' => $space,
          'groupList' => $pagination,
          'page' => $page,
      ]);
    }

    // public function getQuery($space){
    //
    //     $repository = $this->getDoctrine()
    //       ->getRepository('StewieUserBundle:Group');
    //
    //     $query = $repository->createQueryBuilder('g')
    //       ->andWhere(':users MEMBER OF g.users')
    //       ->setParameter('users', $user)
    //       ->orderBy('g.id', 'ASC');
    //
    //       return $query
    //         ->getQuery();
    //
    // }
}
