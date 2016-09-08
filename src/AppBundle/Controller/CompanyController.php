<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Form\CompanyType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
            try {
                $em->flush();
                
                $this->addFlash('success', 'Successfuly Inserted');
            } catch (UniqueConstraintViolationException $ucve) {
                $error = $this->createFormError('error.company.already_exists');
                $form->addError($error);
            }
        }
        
        return $this->render('AppBundle:Company:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/company/edit/{id}", name="company_edit", methods={"GET", "PUT"})
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
            $em->flush();
            
            $this->addFlash('success', 'Successfuly updated');
        }
        
        return $this->render('AppBundle:Company:edit.html.twig', [
            'form' => $form->createView(),
            'edit' => true,
        ]);
    }
}
