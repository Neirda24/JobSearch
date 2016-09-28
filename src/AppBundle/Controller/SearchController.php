<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Entity\Search;
use AppBundle\Entity\Search\Details;
use AppBundle\Form\Company\ListCompanyType;
use AppBundle\Form\Search\DetailsType;
use AppBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/dashboard")
 */
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
            
            return $this->redirectToRoute('dashboard');
        }
        
        return $this->render('AppBundle:Search:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/search/{id}/{company_id}/details/create", options={"expose"=true}, name="search_details_create", methods={"GET", "POST"})
     * @Security("search.isOwner(user)")
     * @ParamConverter("company", options={"id" = "company_id"})
     */
    public function createDetailsAction(Request $request, Search $search, Company $company)
    {
        $details = new Details();
        $details->setSearch($search);
        $details->setCompany($company);
        $form = $this->createForm(DetailsType::class, $details, [
            'method' => Request::METHOD_POST,
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($details);
            $em->flush();
            
            return $this->redirectToRoute('search_view', ['id' => $search->getId(), '_fragment' => $details->getId()]);
        }
        
        return $this->render('AppBundle:Search/Details:create.html.twig', [
            'form'    => $form->createView(),
            'search'  => $search,
            'company' => $company,
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
     * @Route("/search/{id}", name="search_view")
     * @Security("search.isOwner(user)")
     */
    public function dashboardSearchAction(Search $search)
    {
        $em                = $this->getDoctrine()->getManager();
        $companyRepository = $em->getRepository('AppBundle:Company');
        
        $companies = $companyRepository->fetchGroupedForDashboard($search);
        
        $addCompanyForm = $this->createForm(ListCompanyType::class, null, [
            'search' => $search,
        ]);
        
        return $this->render('AppBundle:Front:dashboard/search.html.twig', [
            'search'         => $search,
            'companies'      => $companies,
            'addCompanyForm' => $addCompanyForm->createView(),
        ]);
    }
}
