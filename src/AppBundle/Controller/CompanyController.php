<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Form\CompanyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company/list", name="company_list", methods={"GET"})
     */
    public function listAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Company');
        
        $companies = $repository->findAll();
        
        return $this->render('AppBundle:Company:list.html.twig', [
            'companies' => $companies,
        ]);
    }
    
    /**
     * @Route("/company/create", name="company_create", methods={"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function createAction(Request $request)
    {
        $company = new Company();
        $form    = $this->createForm(CompanyType::class, $company, [
            'method' => Request::METHOD_POST,
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush($company);
            
            if ($request->query->has('search')) {
                return $this->redirectToRoute('search_details_create', [
                    'id'         => $request->query->get('search'),
                    'company_id' => $company->getId(),
                ]);
            } else {
                return $this->redirectToRoute('company_view', ['id' => $company->getId()]);
            }
        }
        
        return $this->render('AppBundle:Company:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/company/edit/{id}", name="company_edit", methods={"GET", "PUT"})
     * @Security("company.isOwner(user) or is_granted('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Company $company)
    {
        $form = $this->createForm(CompanyType::class, $company, [
            'method' => Request::METHOD_PUT,
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush($company);
            
            if ($request->query->has('search')) {
                return $this->redirectToRoute('search_view', ['id' => $request->query->get('search'), '_fragment' => $company->getId()]);
            } else {
                return $this->redirectToRoute('company_view', ['id' => $company->getId()]);
            }
        }
        
        return $this->render('AppBundle:Company:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/company/view/{id}", name="company_view", methods={"GET"})
     */
    public function viewAction(Company $company)
    {
        return $this->render('AppBundle:Company:view.html.twig', [
            'company' => $company,
        ]);
    }
}
