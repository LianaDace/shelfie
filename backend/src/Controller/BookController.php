<?php

namespace App\Controller;

use App\Entity\Book;
use App\Enum\BookStatus;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class BookController extends AbstractController
{
    #[Route('/api/books', methods: ['GET'])]
    public function index(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();

        $data = array_map(fn($book) => [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'status' => $book->getStatus()->value,
        ], $books);

        return $this->json($data);
    }

    #[Route('/api/books', name: 'api_books_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setAuthor($data['author']);
        $book->setStatus(BookStatus::from($data['status'] ?? 'want-to-read'));
        $book->setCreatedAt(new \DateTimeImmutable());

        $error = $validator->validate($book);
        if (count($error) > 0) {
            $errorMessage = [];
            foreach ($error as $violation) {
                $errorMessage[] = $violation->getMessage();
            }
            return $this->json(['error' => $errorMessage], 400);
        }

        $em->persist($book);
        $em->flush();

        return $this->json([
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'status' => $book->getStatus()->value,
        ], 201);
    }

    #[Route('/api/books/edit/{id}', name: 'edit_book', methods: ['PUT'])]
    public function update(EntityManagerInterface $em, ValidatorInterface $validator, Request $request, int $id): JsonResponse
    {
        $book = $em->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $data = json_decode($request->getContent(), true);

        $book->setTitle($data['title'] ?? $book->getTitle());
        $book->setAuthor($data['author'] ?? $book->getAuthor());
        $book->setStatus(
            isset($data['status']) ? BookStatus::from($data['status']) : $book->getStatus()
        );

        $errors = $validator->validate($book);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['error' => $errorMessages], 400);
        }

        $em->flush();

        return $this->json([
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'status' => $book->getStatus()->value,
        ]);
    }

    #[Route('/api/books/delete/{id}', name: 'api_book_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, int $id): JsonResponse
    {
        $repository = $em->getRepository(Book::class);
        $book = $repository->find($id);

        if (!$book) {
            return $this->json(['error' => 'Book not found'], 404);
        }

        $em->remove($book);
        $em->flush();

        return $this->json([
            'message' => 'Book deleted successfully',
        ]);
    }

    #[Route('/api/books/status-options', name: 'book_status_options', methods: ['GET'])]
    public function bookReadingStatus(): JsonResponse
    {
        $option = array_map(
            fn(BookStatus $status) => [
                'label' => $this->formatStatusLabel($status),
                'value' => $status->value,
            ],
            BookStatus::cases()
        );
        return $this->json($option);
    }

    private function formatStatusLabel(BookStatus $status): string
    {
        return match ($status) {
            BookStatus::Reading => 'Currently Reading',
            BookStatus::Finished => 'Finished',
            BookStatus::WantToRead => 'Want to Read',
        };
    }
}
