<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 12/12/16
 * Time: 10:05
 */

namespace AppBundle\Command;


use AppBundle\Entity\Actor;
use AppBundle\Entity\Movie;
use AppBundle\Services\MovieGenerator;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportMoviesCommand extends ContainerAwareCommand
{
    const MAX_RESULTS_PER_PAGE = 10;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var MovieGenerator
     */
    private $movieCollector;

    protected function configure()
    {
        $this
            ->setName('app:movies:import')
            ->setDescription('Import new movies.')
            ->addArgument('search', InputArgument::REQUIRED, 'The search string.')
            ->addArgument('pages', InputArgument::REQUIRED, 'The number of page to retrieve (use 0 for all pages).')
            ->setHelp("")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->getContainer()->get("doctrine")->getManager();
        $this->movieCollector = $this->getContainer()->get('app_movie_generator');
        $pages = $input->getArgument('pages');
        $search = $input->getArgument('search');
        $firstPageMovies = $this->movieCollector->getMovies($search);
        $totalResult = $firstPageMovies->totalResults;
        $maxPage = (int)($totalResult / self::MAX_RESULTS_PER_PAGE);
        if ($pages == 0 or $pages > $maxPage) {
            $pages = $maxPage;
        }
        $progress = new ProgressBar($output, $pages);
        $this->insertMovies($firstPageMovies->Search);
        $progress->advance();
        for ($i=2; $i<=$pages; $i++){
            $movies = $this->movieCollector->getMovies($search, $i);
            $this->insertMovies($movies->Search);
            $progress->advance();
        }
        $progress->finish();
        $output->writeln('');
    }

    /**
     * @param $movies array
     */
    private function insertMovies($movies)
    {
        foreach($movies as $movie) {
            $id = $movie->imdbID;
            /**
             * @var $currentMovie Movie
             */
            $currentMovie = $this->manager->find('AppBundle:Movie', $id);
            if (is_null($currentMovie)) {
                $currentMovie = new Movie();
                $currentMovie->setId($movie->imdbID);
                $currentMovie->setTitle($movie->Title);
                $currentMovie->setYear($movie->Year);
                $currentMovie->setCover($movie->Poster);
            }
            if (0 === count($currentMovie->getActors())){
                $fullMovie = $this->movieCollector->getMovie($currentMovie->getId());
                $currentMovie->setSummary($fullMovie->Plot);
                $actors = explode(",", $fullMovie->Actors);
                foreach ($actors as $actor){
                    $actor = trim($actor);
                    /**
                     * @var $currentActor Actor
                     */
                    $currentActor = $this->manager->getRepository('AppBundle:Actor')->findOneByName($actor);
                    if (is_null($currentActor)) {
                        $currentActor = new Actor();
                        $currentActor->setName(trim($actor));
                    }
                    $currentActor->addMovie($currentMovie);
                    $this->manager->persist($currentActor);
                    $currentMovie->addActor($currentActor);
                }
                $this->manager->persist($currentMovie);
                $this->manager->flush();
            }
        }
        $this->manager->flush();
        $this->manager->clear();
    }
}