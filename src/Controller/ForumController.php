<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Forum;
use App\Form\NewCommentType;
use App\Form\NewForumType;
use App\Gateway\CommentGateway;
use App\Gateway\ForumGateway;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/forums", name="app_forums")
     * @param ForumGateway $forumGateway
     * @param Request $request
     * @param $commentGateway
     * @return RedirectResponse|Response
     */
    public function addForum(
        ForumGateway $forumGateway,
        Request $request,
        CommentGateway $commentGateway
    )
    {
        $form = $this->createForm(NewForumType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $forumGateway->save($form->getData());
            $this->addFlash('success', 'Nouveau forum créé avec succès !');

            return $this->redirectToRoute('app_forum_display', [
                'slug' => $form->getData()->getSlug()
            ]);
        }

        $forums = $forumGateway->paginatedListForums($request->query->getInt('page', 1));

        $reportedComments = $commentGateway->paginatedReportedCommentsList(
            $request->query->getInt('page', 1)
        );

        return $this->render('forum/index.html.twig', [
            'new_forum_form' => $form->createView(),
            'forums' => $forums,
            'reported' => $reportedComments
        ]);
    }

    /**
     * @Route("/forum/{slug}", name="app_forum_display", methods="GET|POST")
     * @param Forum $forum
     * @param Request $request
     * @param CommentGateway $commentGateway
     * @return RedirectResponse|Response
     */
    public function showForum(
        Forum $forum,
        Request $request,
        CommentGateway $commentGateway
    )
    {
        $comment = new Comment();
        $form = $this->createForm(NewCommentType::class, $comment);
        $form->handleRequest($request);
        if ($this->getUser()) {
            // Get the pseudo of the connected user
            $userPseudo = $this->getUser()->getPseudo();
            $comment->setAuthor($userPseudo);
            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setForum($forum);
                $commentGateway->save($form->getData());
                $this->addFlash('success', 'Votre commentaire a bien été ajouté !');
                return $this->redirectToRoute('app_forum_display', [
                    'slug' => $forum->getSlug()
                ]);
            }
        }

        $comments = $commentGateway->paginatedCommentsList(
            $forum->getId(),
            $request->query->getInt('page', 1));

        $reportedComments = $commentGateway->paginatedReportedCommentsList(
            $request->query->getInt('page', 1)
        );

        return $this->render('forum/forum.html.twig', [
            'comments' => $comments,
            'forum' => $forum,
            'comment_form' => $form->createView(),
            'reported' => $reportedComments
        ]);
    }
}
