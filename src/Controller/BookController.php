<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/{id<\d+>?1}', name: 'app_book_fetch', methods: ['GET'])]
    public function fetch(int $id, BookRepository $repository): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'Book Fetch',
        ]);
    }
}
