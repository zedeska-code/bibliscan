<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class BookService
{
    private BookRepository $book_repository;
    private HttpClientInterface $http_client;

    public function __construct(BookRepository $bookRepository, HttpClientInterface $httpClient)
    {
        $this->book_repository = $bookRepository;
        $this->http_client = $httpClient;
    }

    public function getAllBooks(): array
    {
        $book = $this->book_repository->findAll();
        return $book;
    }

    public function ISBNFetch(int $isbn): array
    {
        $response = $this->http_client->request(
            'GET',
            "https://openlibrary.org/isbn/$isbn.json"
        );
        $content = $response->toArray();
        return $content;
    }
    public function authorFetch(int $isbn)
    {
        $response = $this->http_client->request(
            'GET',
            "https://openlibrary.org/search.json?isbn=$isbn&fields=author_name"
        );
        $data = $response->toArray();
        $author = $data['docs'][0]['author_name'][0];
        return $author;
    }

    public function coverFetch(int $isbn)
    {
        return "https://covers.openlibrary.org/b/isbn/$isbn-M.jpg";
    }
    public function findBookByIsbn(int $isbn): Book
    {
        $book = $this->book_repository->findOneBy(['isbn' => $isbn]);
        return $book;
    }
}
