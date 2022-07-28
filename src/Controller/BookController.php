<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book', name: 'app_book_')]
class BookController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/{id<\d+>?1}', name: 'fetch', methods: ['GET'])]
    public function fetch(int $id, BookRepository $repository): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'Book Fetch',
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, BookRepository $repository): Response
    {
        $book = new Book();
        $bookForm = $this->createForm(BookType::class, $book);
        $bookForm->handleRequest($request);

        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $repository->add($book, true);

            return $this->redirectToRoute('app_book_index');
        }

        return $this->renderForm('book/new.html.twig', [
            'bookForm' => $bookForm,
        ]);
    }
}
