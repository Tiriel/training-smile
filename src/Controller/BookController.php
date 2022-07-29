<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Security\Voter\BookVoter;
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
        //if (!$this->isGranted('ROLE_ADMIN')) {
        //    $this->denyAccessUnlessGranted(BookVoter::VIEW, $book);
        //}
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
            $this->denyAccessUnlessGranted('ROLE_PUBLISHER');
            $repository->add($book, true);

            return $this->redirectToRoute('app_book_index');
        }

        return $this->renderForm('book/new.html.twig', [
            'bookForm' => $bookForm,
        ]);
    }
}
