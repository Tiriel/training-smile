<?php

namespace App\Transformer;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;

class OmdbMovieTransformer
{
    public function __construct(private GenreRepository $genreRepository)
    {}

    public function transform(array $data): Movie
    {
        $genres = explode(', ', $data['Genre']);
        $date = $data['Released'] === 'N/A' ? $data['Year'] : $data['Released'];
        $movie = (new Movie())
            ->setTitle($data['Title'])
            ->setPoster($data['Poster'])
            ->setCountry($data['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setRated($data['Rated'])
            ->setOmdbId($data['imdbID'])
            ->setPrice(5.0)
        ;

        foreach ($genres as $genre) {
            $genreEnt = $this->genreRepository->findOneBy(['name' => $genre]) ?? (new Genre())
                ->setName($genre)
                ->setPoster($data['Poster'])
            ;
            $movie->addGenre($genreEnt);
        }

        return $movie;
    }
}

