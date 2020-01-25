<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    /**
     * @Route("/", name="app_home")
     */
    public function home()
    {
        return $this->render('library/home.html.twig');
    }

    /**
     * @Route("/books", name="books_list")
     */
    public function listBooks()
    {
        $repo = $this->getDoctrine()->getRepository(Book::class);
        $books = $repo->findAll();
        return $this->render('library/booksList.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * @Route("/forums", name="app-forums")
     */
    public function forums()
    {
        return $this->render('library/forums.html.twig');
    }
}
