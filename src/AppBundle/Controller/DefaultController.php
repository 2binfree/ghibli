<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $movies = $this->container->get('app_movie_generator')->getMovies();

        return $this->render(
            'AppBundle:Default:index.html.twig',
            array(
                'movies' => $movies,
            )
        );
    }
}
