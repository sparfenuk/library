<?php

namespace App\Controller\Api\v1;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController
 * @package App\Controller
 */

class BookController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ObjectRepository */
    private $objectRepository;

    private $serializer;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Book::class);
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

    }

    public function postBook(Request $request)
    {
        $book = new Book();
        $book->setTitle($request->get(‘title’));
        $book->setAuthor($request->get(‘author’));
        $book->setLength($request->get(‘length’));

        $this->entityManager->persist($book);
        $this->entityManager->flush();

    }


    public function getBook(int $id)
    {
        $book = $this->objectRepository->find($id);
        return new Response(
            $this->serializer->serialize($book, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    public function getBooks()
    {
        $books = $this->objectRepository->findAll();
        return new Response(
            $this->serializer->serialize($books, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    public function putBook(int $id, Request $request)
    {
        $book = $this->objectRepository->find($id);

        $book->setTitle($request->get(‘title’));

        $this->entityManager->persist($book);
        $this->entityManager->flush();
        return new Response($this->serializer->serialize($book, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']);

    }

    public function deleteBook(int $id)
    {
        $this->entityManager->remove($this->objectRepository->find($id));
        $this->entityManager->flush();
        return new Response(true,
            Response::HTTP_OK,
            ['Content-type' => 'application/json']);
    }
}
