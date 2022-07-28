<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default_index')]
    public function index(MovieRepository $repository): Response
    {
        $movies = $repository->findBy([], ['id' => 'DESC'], 6);

        return $this->render('default/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/contact', name: 'app_default_contact')]
    public function contact(): Response
    {
        $contactForm = $this->createForm(ContactType::class, new ContactDTO());

        return $this->renderForm('default/contact.html.twig', [
            'contactForm' => $contactForm,
        ]);
    }
}
