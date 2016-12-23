<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $phrase = strtolower("Seul, on va plus vite, ensemble, on va plus loin.");
        $result = array();
        for($i=0; $i<strlen($phrase); $i++){
            if (preg_match("/^[a-z]+$/", $phrase[$i])) {
                if (!isset($result[$phrase[$i]])){
                    $result[$phrase[$i]] = 1;
                } else {
                    $result[$phrase[$i]]++;
                }
            }
        }
        arsort($result);
        dump($result);


        return $this->render('AppBundle:Default:index.html.twig');
    }
    
    public function animateAction()
    {
        $this->container->get('profiler')->disable();
        return $this->render('AppBundle:Default:bzzz.html.twig');
    }
}
