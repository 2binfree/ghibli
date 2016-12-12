<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppBundle:Default:index.html.twig');
    }
    
    public function animateAction()
    {
        $this->container->get('profiler')->disable();
        return $this->render('AppBundle:Default:bzzz.html.twig');
    }
}
