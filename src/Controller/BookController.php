<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'book_')]
final class BookController extends AbstractController
{
    private BookService $book_service;

    public function __construct(BookService $bookService)
    {
        $this->book_service = $bookService;
    }

    #[Route('/', name: 'index')]
    public function list(): Response
    {
        $books = $this->book_service->getAllBooks();
        return $this->render('index.html.twig', [
            'books' => $books,
        ]);
    }

    ##[Route('/{isbn}', name: 'details')]
    #public function details($isbn): Response
    #{
    #    return $this->render('book/index.html.twig', [
    #        'controller_name' => 'BookController',
    #    ]);
    #}

    #[Route('/new/{isbn?}', 'new')]
    public function new(Request $request, ?int $isbn): Response
    {
        $data = $this->book_service->ISBNFetch($isbn);

        $title = $data['title'];
        $cover = $this->book_service->coverFetch($isbn);
        $author = $this->book_service->authorFetch($isbn);
        $publisher = $data['publishers'][0];
        $publishDate = date_create($data['publish_date']);

        $book = new Book();
        $book->setIsbn($isbn);
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setCoverUrl($cover);
        $book->setPublisher($publisher);
        $book->setPublicationDate($publishDate);

        $form = $this->createForm(BookType::class, $book);

        return $this->render(
            'book/new.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}


// $form->handleRequest($request);
// if ($form->isSubmitted() && $form->isValid()) {
//     $data = $form->getData();
//     $isbn = $data['isbn'];
//     $results = $this->search_service->ISBNFetch($isbn);
//     $book = $results['docs'];
//     $cover = "https://covers.openlibrary.org/b/isbn/$isbn-M.jpg";
