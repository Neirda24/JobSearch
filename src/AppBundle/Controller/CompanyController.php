<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Form\CompanyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

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
            
            // creating the ACL
            $aclProvider    = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($company);
            $acl            = $aclProvider->createAcl($objectIdentity);
            
            // retrieving the security identity of the currently logged-in user
            $user             = $this->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);
            
            // grant operator access
            $maskBuilder = new MaskBuilder();
            $maskBuilder
                ->add(MaskBuilder::MASK_OPERATOR)
                ->remove(MaskBuilder::MASK_DELETE)
            ;
            
            $mask = $maskBuilder->get();
            $acl->insertObjectAce($securityIdentity, $mask);
            $aclProvider->updateAcl($acl);
            
            return $this->redirectToRoute('company_view', ['id' => $company->getId()]);
        }
        
        return $this->render('AppBundle:Company:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/company/edit/{id}", name="company_edit", methods={"GET", "PUT"})
     * @Security("is_granted('EDIT', company) or has_role('ROLE_ADMIN')")
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
    
            return $this->redirectToRoute('company_view', ['id' => $company->getId()]);
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
