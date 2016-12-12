<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 12/12/16
 * Time: 10:05
 */

namespace AppBundle\Command;


use AppBundle\Entity\Movie;
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
        $moviesGetter = $this->getContainer()->get('app_movie_generator');
        $pages = $input->getArgument('pages');
        $search = $input->getArgument('search');
        $firstPageMovies = $moviesGetter->getMovies($search);
        $totalResult = $firstPageMovies->totalResults;
        $maxPage = (int)($totalResult / self::MAX_RESULTS_PER_PAGE);
        if ($pages == 0 or $pages > $maxPage) {
            $pages = $maxPage;
        }
        $progress = new ProgressBar($output, $pages);
        $this->insertMovies($firstPageMovies->Search);
        $progress->advance();
        for ($i=2; $i<=$pages; $i++){
            $movies = $moviesGetter->getMovies($search, $i);
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
            $result = $this->manager->find('AppBundle:Movie', $id);
            if (is_null($result)) {
                $newMovie = new Movie();
                $newMovie->setId($movie->imdbID);
                $newMovie->setTitle($movie->Title);
                $newMovie->setSummary($movie->Title);
                $newMovie->setYear($movie->Year);
                $newMovie->setCover($movie->Poster);
                $this->manager->persist($newMovie);
            }
        }
        $this->manager->flush();
        $this->manager->clear();
    }
}