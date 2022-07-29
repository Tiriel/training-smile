<?php

namespace App\Provider;

use App\Consumer\OMDbApiConsumer;
use App\Entity\Movie;
use App\Entity\User;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class MovieProvider
{
    public function __construct(
        private MovieRepository $movieRepository,
        private OMDbApiConsumer $consumer,
        private OmdbMovieTransformer $transformer,
        private Security $security
    ) {}

    public function getMovieByTitle(string $title)
    {
        return $this->getOneMovie(OMDbApiConsumer::MODE_TITLE, $title);
    }

    public function getMovieById(string $id): Movie
    {
        return $this->getOneMovie(OMDbApiConsumer::MODE_ID, $id);
    }

    private function getOneMovie(string $mode, string $value)
    {
        if (!$this->security->isGranted('ROLE_USER')) {
            throw new UnauthorizedHttpException('You must be logged in to add a movie.');
        }

        $movie = $this->transformer->transform(
                $this->consumer->consume($mode,  $value)
            );

        if ($entity = $this->movieRepository->findOneBy(['title' => $movie->getTitle()])) {
            return $entity;
        }

        $user = $this->security->getUser();
        assert($user instanceof User);

        $movie->setAddedBy($user);
        $this->movieRepository->add($movie, true);

        return $movie;
    }
}

