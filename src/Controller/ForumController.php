<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Forum;
use App\Entity\Comment;
use App\Form\NewCommentType;
use App\Form\NewForumType;
use App\Gateway\CommentGateway;
use App\Gateway\ForumGateway;
use App\Repository\CommentRepository;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ForumController extends AbstractController
{
    /**
     * @Route("/forums", name="app_forums")
     */
    public function addForum(
        ForumGateway $forumGateway,
        Request $request,
        PaginatorInterface $paginator,
        ForumRepository $forumRepo
    ) {
        $form = $this->createForm(NewForumType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $forumGateway->save($form->getData());
            $this->addFlash('success', 'Nouveau forum créé avec succès !');

            return $this->redirectToRoute('app_forum_display', [
                'slug' => $form->getData()->getSlug()
            ]);
        }

        // $forums = $forumGateway->paginatedListForums($request->query->getInt('page', 1));

        $query = $forumRepo->findAllQuery();
        $forums = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('forum/index.html.twig', [
            'new_forum_form' => $form->createView(),
            'forums' => $forums
        ]);
    }

    /**
     * @Route("/forum/{slug}", name="app_forum_display", methods="GET|POST")
     */
    public function showForum(
        string $slug,
        EntityManagerInterface $em,
        Request $request,
        //CommentGateway $commentGateway,
        PaginatorInterface $paginator,
        CommentRepository $commentRepo
    ) {
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
            return $this->redirectToRoute('app_forum_display', [
                'slug' => $slug
            ]);
        }

        $query = $commentRepo->findAllByForum($forumId);
        $comments = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('forum/forum.html.twig', [
            'comments' => $comments,
            'forum' => $forum,
            'comment_form' => $form->createView(),
        ]);
    }
}
