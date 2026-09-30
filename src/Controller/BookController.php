<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Form\FilterType;
use App\Service\BookService;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/', name: 'index', methods: ['POST', 'GET'])]
    public function list(Request $request): Response
    {
        $books = $this->book_service->getAllBooks();

        $form = $this->createForm(
            FilterType::class,
            null,
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $titleQuery = trim($data['title']);
            $authorQuery = trim($data['author']);

            $books = $this->book_service->getBooksByTitleOrAuthor($titleQuery, $authorQuery);
        }
        return $this->render('index.html.twig', [
            'books' => $books,
            'form' => $form,
        ]);
    }

    #[Route('/new/{isbn?}', 'new', methods: ['GET'])]
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

        $form = $this->createForm(BookType::class, $book, [
            'method' => 'GET',
        ]);
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
    #[Route('/book/{isbn}', 'details', methods: ['GET', 'POST'])]
    public function update(EntityManagerInterface $entityManager, Request $request, int $isbn): Response
    {
        $book = $this->book_service->findBookByIsbn($isbn);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();

            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Informations modifiées');
            return $this->redirectToRoute('book_details', [
                'isbn' => $book->getIsbn(),
            ]);
        }
        return $this->render('book/index.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }
    #[Route('/book/{isbn}/delete', 'delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $entityManager, Request $request, int $isbn): Response
    {
        $book = $this->book_service->findBookByIsbn($isbn);
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $book->getIsbn(), $token)) {
            $entityManager->remove($book);
            $entityManager->flush();

            $this->addFlash('success', 'Livre supprimé avec succès.');
        } else {
            $this->addFlash('success', 'Jeton CSRF invalide, suppression annulée');
        }
        return $this->redirectToRoute('book_index');
    }
}
