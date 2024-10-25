<?php

namespace App\Controller;


use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(PokemonRepository $pokemonRepository): \Symfony\Component\HttpFoundation\Response
    {
        $pokemons = $pokemonRepository->findAll();

        return $this->render('index.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }
}
