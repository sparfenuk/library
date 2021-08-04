<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(Request $request, AuthorRepository $authorRepository,PaginatorInterface $paginator): Response
    {
        $authors = $authorRepository->getAuthorsWithBooks();
        $pagination = $paginator->paginate(
            $authors,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('home_page.html.twig',[
            'pagination' => $pagination,
        ]);
    }

    public function books(Request $request, BookRepository $bookRepository,PaginatorInterface $paginator, ?int $author_id=null): Response
    {

        if($author_id){
            $books = $bookRepository->findBy(['author' => $author_id], ['createdAt' => 'DESC']);
        } else {
            $books = $bookRepository->findBy([], ['createdAt' => 'DESC']);
        }
        $pagination = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('book/index.html.twig',[
            'pagination' => $pagination,
        ]);
    }
}
