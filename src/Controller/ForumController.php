<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Forum;
use App\Entity\Comment;
use App\Form\NewCommentType;
use App\Form\NewForumType;
use Doctrine\ORM\EntityManagerInterface;


class ForumController extends AbstractController
{
    /**
     * @Route("/forums", name="app_forums")
     */
    public function addForum(EntityManagerInterface $em, Request $request, SluggerInterface $slugger)
    {
        $forum = new Forum();
        $form = $this->createForm(NewForumType::class, $forum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $topic = $forum->getTopic();
            // Convert to an Url format
            $slugger = new AsciiSlugger('fr');
            $slug = $slugger->slug($topic);
            $forum->setSlug($slug);
            $forum->setCreationDate(new \DateTime());
            //$forum->setAuthor();
            //$em->persist($data);
            //$em->flush();
            $this->addFlash('success', 'Nouveau forum créé avec succès !');
            //return $this->redirectToRoute('app_forums');
        }
        $repo = $this->getDoctrine()->getRepository(forum::class);
        $forums = $repo->findAll();

        return $this->render('forum/index.html.twig', [
            'new_forum_form' => $form->createView(),
            'forums' => $forums
        ]);
    }

    /**
     * @Route("/forum/{slug}", name="app_forum_display")
     */
    public function showForum(string $slug, EntityManagerInterface $em, Request $request)
    {

        $forumRepo = $this->getDoctrine()->getRepository(forum::class);
        $forum = $forumRepo->findOneBy(['slug' => $slug]);
        $forumId = $forum->getId();
        dump($forumId);

        $comment = new Comment();
        $form = $this->createForm(NewCommentType::class, $comment);
        $form->handleRequest($request);
        // Get the pseudo of the connected user
        $userPseudo = $this->getUser()->getPseudo();
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $comment->setCreationDate(new \DateTime());
            $comment->setAuthor($userPseudo);
            $comment->setStatus(0);
            $comment->setForum($forum);
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire à été ajouté !');
        }
        $repo = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $repo->findBy(['forum' => $forumId]);
        dump($comments);
        return $this->render('forum/forum.html.twig', [
            'comments' => $comments,
            'forum' => $forum,
            'comment_form' => $form->createView()
        ]);
    }

    public function reportMessage()
    {
        $forum = $this->getDoctrine()->getRepository(Forum::class);
    }
}
