<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fruit', name: 'app_fruit')]
class GetFruit
{
    public function __invoke()
    {
        return new Response('Yay fruit');
    }
}