<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('library/home.html.twig');
    }

    /**
     * @Route("/books", name="books_list")
     */
    public function list()
    {
        $repo = $this->getDoctrine()->getRepository(Book::class);
        $books = $repo->findAll();
        return $this->render('library/booksList.html.twig', [
            'books' => $books
        ]);
    }
}
