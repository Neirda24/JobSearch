<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Entity\Company\Collaborator;
use AppBundle\Form\Company\CollaboratorType;
use AppBundle\Form\CompanyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/collaborator")
 */
class CollaboratorController extends AbstractController
{
    /**
     * @Route("/list", name="collaborator_list", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function listAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Company\Collaborator');

        $collaborators = $repository->findBy([
            'addedBy' => $this->getUser(),
        ]);

        return $this->render('AppBundle:Collaborator:list.html.twig', [
            'collaborators' => $collaborators,
        ]);
    }

    /**
     * @Route("/create", name="collaborator_create", methods={"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function createAction(Request $request)
    {
        $collaborator = new Collaborator();
        $form    = $this->createForm(CollaboratorType::class, $collaborator, [
            'method' => Request::METHOD_POST,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($collaborator);
            $em->flush($collaborator);

            if ($request->query->has('search')) {
                return $this->redirectToRoute('search_view', ['id' => $request->query->get('search')]);
            } else {
                return $this->redirectToRoute('collaborator_list');
            }
        }

        return $this->render('AppBundle:Collaborator:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="collaborator_edit", methods={"GET", "PUT"})
     * @Security("collaborator.wasAddedBy(user) or is_granted('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Collaborator $collaborator)
    {
        $form = $this->createForm(CollaboratorType::class, $collaborator, [
            'method' => Request::METHOD_PUT,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($collaborator);
            $em->flush($collaborator);

            if ($request->query->has('search')) {
                return $this->redirectToRoute('search_view', ['id' => $request->query->get('search')]);
            } else {
                return $this->redirectToRoute('collaborator_list');
            }
        }

        return $this->render('AppBundle:Collaborator:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/view/{id}", name="collaborator_view", methods={"GET"})
     * @Security("collaborator.wasAddedBy(user) or is_granted('ROLE_ADMIN')")
     */
    public function viewAction(Collaborator $collaborator)
    {
        return $this->render('AppBundle:Collaborator:view.html.twig', [
            'collaborator' => $collaborator,
        ]);
    }
}
