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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ForumController extends AbstractController
{
    /**
     * @Route("/forums", name="app_forums")
     */
    public function addForum(EntityManagerInterface $em, Request $request, SluggerInterface $slugger, PaginatorInterface $paginator)
    {
        $forum = new Forum();
        $form = $this->createForm(NewForumType::class, $forum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $topic = $forum->getTopic();
            // Convert to an url format
            $slugger = new AsciiSlugger('fr');
            $slug = $slugger->slug($topic);
            $forum->setSlug(strtolower($slug));
            $forum->setCreationDate(new \DateTime());
            $em->persist($forum);
            $em->flush();
            $this->addFlash('success', 'Nouveau forum créé avec succès !');
            return $this->redirectToRoute('app_forum_display', [
                'slug' => $forum->getSlug()
            ]);
        }

        $query = $this->getDoctrine()->getRepository(forum::class)->findAll();
        $forums = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/

        );
        return $this->render('forum/index.html.twig', [
            'new_forum_form' => $form->createView(),
            'forums' => $forums
        ]);
    }

    /**
     * @Route("/forum/{slug}", name="app_forum_display", methods="GET|POST")
     */
    public function showForum(string $slug, EntityManagerInterface $em, Request $request, PaginatorInterface $paginator)
    {
        $forumRepo = $this->getDoctrine()->getRepository(Forum::class);
        $forum = $forumRepo->findOneBy(['slug' => $slug]);
        $forumId = $forum->getId();

        $comment = new Comment();
        $form = $this->createForm(NewCommentType::class, $comment);
        $form->handleRequest($request);
        if ($this->getUser()) {
            // Get the pseudo of the connected user
            $userPseudo = $this->getUser()->getPseudo();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $comment->setCreationDate(new \DateTime());
            $comment->setAuthor($userPseudo);
            $comment->setStatus(0);
            $comment->setForum($forum);
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire à bien été ajouté !');
        }
        $query = $this->getDoctrine()->getRepository(Comment::class);
        $messages = $query->findBy(
            ['forum' => $forumId],
            ['creationDate' => 'DESC']
        );

        $comments = $paginator->paginate(
            $messages,
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/

        );
        return $this->render('forum/forum.html.twig', [
            'comments' => $comments,
            'forum' => $forum,
            'comment_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/forum/delete/{slug}/{commentId}", name="app_forum_remove_comment")
     */
    public function removeComment(int $commentId, string $slug, EntityManagerInterface $em)
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commentId);
        dump($comment);
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('app_forum_display', [
            'slug' => $slug,
        ]);
    }
}
