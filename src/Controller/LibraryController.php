<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Forum;
use App\Form\NewForumType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/forum", name="app_forum")
     */
    public function forum()
    {
        return $this->render('library/forum.html.twig');
    }

    /**
     * @Route("/forum", name="app_forum")
     */
    public function createForum(EntityManagerInterface $em, Request $request)
    {
        $forum = new Forum();
        $form = $this->createForm(NewForumType::class, $forum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $forum->setCreationDate(new \DateTime());
            //$forum->setAuthor();
            $data = $form->getData();
            dump($data);
            //$em->persist($data);
            //$em->flush();
            $this->addFlash('success', 'Nouveau forum créé avec succès !');
        }

        return $this->render('library/forum.html.twig', [
            'new_forum_form' => $form->createView()
        ]);
    }
}
