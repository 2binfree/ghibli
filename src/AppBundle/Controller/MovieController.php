<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Movie controller.
 *
 */
class MovieController extends Controller
{
    /**
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page = 1)
    {
        $start = ($page - 1) * MovieRepository::MAX_RESULT;
        $nbResults = MovieRepository::MAX_RESULT;
        $movies = $this->getDoctrine()->getManager()->getRepository('AppBundle:Movie')->getAllMovies($start, $nbResults);
        $total = count($movies);
        $maxPage = (int)$total/MovieRepository::MAX_RESULT;
        $startPage = 1;
        $endPage = 10;
        if ($maxPage > 10){
            if($page > 5){
                $startPage = $page - 4;
                $endPage = $page + 5;
            }
        }

        return $this->render('movie/index.html.twig', array(
            'movies'    => $movies,
            'page'      => $page,
            'maxPage'   => $maxPage,
            'total'     => $total,
            'startPage' => $startPage,
            'endPage'   => $endPage
        ));
    }

    /**
     * Finds and displays a movie entity.
     *
     */
    public function showAction(Movie $movie)
    {

        return $this->render('movie/show.html.twig', array(
            'movie' => $movie,
        ));
    }
}
