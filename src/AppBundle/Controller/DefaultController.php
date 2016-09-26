<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Search;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Front:index.html.twig');
    }
    
    /**
     * @Route("/dashboard", name="dashboard")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function dashboardAction()
    {
        return $this->render('AppBundle:Front:dashboard.html.twig');
    }
}
