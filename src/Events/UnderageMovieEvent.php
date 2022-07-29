<?php

namespace App\Events;

use App\Entity\Movie;
use Symfony\Contracts\EventDispatcher\Event;

class UnderageMovieEvent extends Event
{
    public const NAME = 'movie.underage';

    public function __construct(private Movie $movie)
    {}

    public function getMovie(): Movie
    {
        return $this->movie;
    }
}