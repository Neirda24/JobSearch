<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Form\CompanyType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class CompanyController extends Controller
{
    /**
     * @Route("/company/list", name="company_list")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Company');
        
        $companies = $repository->findAll();
        
        return $this->render('AppBundle:Company:list.html.twig', [
            'companies' => $companies,
        ]);
    }
    
    /**
     * @Route("/company/create", name="company_create")
     */
    public function createAction(Request $request)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company, [
            'method' => Request::METHOD_POST,
        ]);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            try {
                $em->flush();
    
                $this->get('session')->getFlashBag()->add('success', 'Successfuly inserted');
            } catch (UniqueConstraintViolationException $ucve) {
                $error = new FormError($this->get('translator')->trans('error.company.already_exists', [], 'validators'));
                $form->addError($error);
            }
        }
        
        return $this->render('AppBundle:Company:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
