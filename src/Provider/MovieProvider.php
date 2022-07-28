<?php

namespace App\Provider;

use App\Consumer\OMDbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;

class MovieProvider
{
    public function __construct(
        private MovieRepository $movieRepository,
        private OMDbApiConsumer $consumer,
        private OmdbMovieTransformer $transformer
    ) {}

    public function getMovieByTitle(string $title)
    {
        $movie = $this->movieRepository->findByLowerTitle($title) ??
            $this->transformer->transform(
                $this->consumer->consume(OMDbApiConsumer::MODE_TITLE, $title)
            );

        if (!$movie->getId()) {
            $this->movieRepository->add($movie, true);
        }

        return $movie;
    }
}

