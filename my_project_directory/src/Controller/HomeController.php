<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(PokemonRepository $pokemonRepository, Request $request): Response
    {
        $name = $request->query->get('name');
        $type = $request->query->get('type');

        $page = max(1, $request->query->getInt('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $queryBuilder = $pokemonRepository->createQueryBuilder('p');

        if ($name) {
            $queryBuilder->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($type) {
            $queryBuilder->andWhere('p.type = :type')
                ->setParameter('type', $type);
        }

        $queryBuilder->setFirstResult($offset)
            ->setMaxResults($limit);

        $pokemons = $queryBuilder->getQuery()->getResult();

        $totalPokemons = $pokemonRepository->createQueryBuilder('p')
            ->select('count(p.id)');

        if ($name) {
            $totalPokemons->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($type) {
            $totalPokemons->andWhere('p.type = :type')
                ->setParameter('type', $type);
        }

        $totalPokemons = $totalPokemons->getQuery()->getSingleScalarResult();
        $totalPages = ceil($totalPokemons / $limit);

        return $this->render('index.html.twig', [
            'pokemons' => $pokemons,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'name' => $name,
            'type' => $type,
        ]);
    }

    #[Route('/pokemon/new', name: 'pokemon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pokemon);
            $entityManager->flush();
            dump($pokemon->getId());

            return $this->redirectToRoute('pokemon_show', ['id' => $pokemon->getId()]);
        }

        return $this->render('pokemon_form.html.twig', [
            'form' => $form->createView(),
            'pokemon' => $pokemon,
        ]);
    }

    #[Route('/pokemon/{id}/edit', name: 'pokemon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('pokemon_show', ['id' => $pokemon->getId()]);
        }

        return $this->render('pokemon_form.html.twig', [
            'form' => $form->createView(),
            'pokemon' => $pokemon
        ]);
    }

    #[Route('/pokemon/{id}', name: 'pokemon_show', methods: ['GET'])]
    public function show(PokemonRepository $pokemonRepository, int $id): Response
    {
        $pokemon = $pokemonRepository->find($id);

        if (!$pokemon) {
            throw $this->createNotFoundException('Pokemon not found');
        }

        return $this->render('pokemon_show.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }

    #[Route('/pokemon/{id}/delete', name: 'pokemon_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pokemon->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pokemon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
