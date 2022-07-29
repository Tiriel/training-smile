<?php

namespace App\Controller;

use App\Consumer\OMDbApiConsumer;
use App\Events\UnderageMovieEvent;
use App\Provider\MovieProvider;
use App\Security\Voter\MovieVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/movie', name: 'app_movie_')]
class MovieController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }

    #[Route('/{title}', name: 'details')]
    public function details(string $title, MovieProvider $provider, EventDispatcherInterface $dispatcher): Response
    {
        $movie = $provider->getMovieByTitle($title);

        if (!$this->isGranted(MovieVoter::VIEW, $movie)) {
            $dispatcher->dispatch(new UnderageMovieEvent($movie), UnderageMovieEvent::NAME);

            $exception = $this->createAccessDeniedException('Access denied');
            $exception->setAttributes(MovieVoter::VIEW);
            $exception->setSubject($movie);

            throw $exception;
        }

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/test', name: 'test')]
    public function menu(OMDbApiConsumer $consumer)
    {
        dd($consumer->consume(OMDbApiConsumer::MODE_TITLE, 'Star Wars'));
        return '';
    }
}
