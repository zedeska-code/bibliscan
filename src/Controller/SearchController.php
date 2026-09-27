<?php

namespace App\Controller;

use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request): Response|array
    {
        $default_data = ['isbn' => ''];
        $form = $this->createForm(SearchType::class, $default_data, [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $isbn = $data['isbn'];
            return $this->redirectToRoute(
                'book_new',
                [
                    'isbn' => $isbn,
                ]
            );
        }
        return $this->render(
            'search/index.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
