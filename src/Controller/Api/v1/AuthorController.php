<?php

namespace App\Controller\Api\v1;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AuthorController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ObjectRepository */
    private $objectRepository;

    private $serializer;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Author::class);
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

    }

    public function postAuthor(Request $request)
    {
        $author = new Author();
        $author->setTitle($request->get(‘title’));
        $author->setAuthor($request->get(‘author’));
        $author->setLength($request->get(‘length’));

        $this->entityManager->persist($author);
        $this->entityManager->flush();

    }


    public function getAuthor(int $id): Response
    {
        $author = $this->objectRepository->find($id);
        return new Response(
            $this->serializer->serialize($author, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    public function getAuthors(): Response
    {
        $authors = $this->objectRepository->findAll();
        return new Response(
            $this->serializer->serialize($authors, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    public function putAuthor(int $id, Request $request): Response
    {
        $author = $this->objectRepository->find($id);

        $author->setTitle($request->get(‘title’));

        $this->entityManager->persist($author);
        $this->entityManager->flush();
        return new Response($this->serializer->serialize($author, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']);

    }

    public function deleteAuthor(int $id): Response
    {
        $this->entityManager->remove($this->objectRepository->find($id));
        $this->entityManager->flush();
        return new Response(true,
            Response::HTTP_OK,
            ['Content-type' => 'application/json']);
    }
}
