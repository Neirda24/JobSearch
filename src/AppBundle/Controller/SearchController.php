<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Search;
use AppBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractController
{
    /**
     * @Route("/search/list", name="search_list", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function listAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Search');
        
        $searches = $repository->findBy([
            'owner' => $this->getUser(),
        ]);
        
        return $this->render('AppBundle:Search:list.html.twig', [
            'searches' => $searches,
        ]);
    }
    
    /**
     * @Route("/search/create", name="search_create", methods={"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function createAction(Request $request)
    {
        $search = new Search();
        $form   = $this->createForm(SearchType::class, $search, [
            'method' => Request::METHOD_POST,
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($search);
            $em->flush($search);
            
            return $this->redirectToRoute('search_view', ['id' => $search->getId()]);
        }
        
        return $this->render('AppBundle:Search:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/search/edit/{id}", name="search_edit", methods={"GET", "PUT"})
     * @Security("search.isOwner(user)")
     */
    public function editAction(Request $request, Search $search)
    {
        $form = $this->createForm(SearchType::class, $search, [
            'method' => Request::METHOD_PUT,
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($search);
            $em->flush($search);
            
            return $this->redirectToRoute('search_view', ['id' => $search->getId()]);
        }
        
        return $this->render('AppBundle:Search:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/search/view/{id}", name="search_view", methods={"GET"})
     * @Security("search.isOwner(user)")
     */
    public function viewAction(Search $search)
    {
        return $this->render('AppBundle:Search:view.html.twig', [
            'search' => $search,
        ]);
    }
}
