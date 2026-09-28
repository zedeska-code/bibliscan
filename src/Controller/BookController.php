<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Service\BookService;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
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

    #[Route('/new/{isbn?}', 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request, ?int $isbn): Response
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
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();

            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Livre ajouté');
            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/new.html.twig',
            [
                'form' => $form,
            ]
        );
    }
    #[Route('/book/{isbn}', 'details')]
    public function update(EntityManagerInterface $entityManager, Request $request, int $isbn): Response
    {
        $book = $this->book_service->findBookByIsbn($isbn);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();


        }


        return $this->render('book/index.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }
}
