<?php

namespace Stewie\WikiBundle\Controller\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

/**
  * @IsGranted("ROLE_USER_GROUP_VIEW")
  */

class ListController extends AbstractController
{
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
    * @Route("/space/list/{page}", defaults={"page": 1}
    *     , requirements={"page": "\d+"}, name="stewie_wiki_space_list")
    */
    public function list($page, Request $request)
    {
      //get data and paginate
      // $paginator  = $this->get('knp_paginator');
      $pagination = $this->paginator->paginate(
      $this->getQuery(), /* query NOT result */
      $request->query->getInt('page', $page)/*page number*/,
            // 10/*limit per page*/
            $this->getParameter('stewie_wiki.max_rows')/*limit per page*/
            // $this->container->getParameter('stewie_user.max_rows')/*limit per page*/
        );
        // $pagination->setTemplate('@SWUser/User/pagination.html.twig');
        $pagination->setTemplate('@StewieWiki/default/pagination.html.twig');

      return $this->render('@StewieWiki/space/list.html.twig', [
          'spaceList' => $pagination,
          'page' => $page,
      ]);
    }

    public function getQuery(){

        $repository = $this->getDoctrine()
          ->getRepository('StewieWikiBundle:Space');

        $query = $repository->createQueryBuilder('s')
          ->orderBy('s.id', 'ASC');

          return $query
            ->getQuery();

    }
}
