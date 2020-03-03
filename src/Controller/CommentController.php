<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Gateway\CommentGateway;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comments/{id}", name="app_forum_remove_comment", methods={"DELETE"})
     * @param Comment $comment
     * @param Request $request
     * @param CommentGateway $commentGateway
     * @return RedirectResponse
     */
    public function removeComment(
        Comment $comment,
        Request $request,
        CommentGateway $commentGateway
    )
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->get('_token'))) {
            $commentGateway->delete($comment);
            $this->addFlash('success', 'Message supprimé avec succès !');
        }

        return $this->redirectToRoute('app_forum_reported_comments', [
            'slug' => $comment->getForum()->getSlug(),
        ]);
    }

    /**
     * @Route("/comments/{id}", name="app_forum_report_comment", methods={"POST"})
     * @param Comment $comment
     * @param CommentGateway $commentGateway
     * @return RedirectResponse
     */
    public function reportComment(
        Comment $comment,
        CommentGateway $commentGateway
    )
    {
        $comment->setStatus(1);
        $commentGateway->save($comment);

        return $this->redirectToRoute('app_forum_display', [
            'slug' => $comment->getForum()->getSlug(),
        ]);
    }

    /**
     * @route("/comments/reported", name="app_forum_reported_comments")
     * @param CommentGateway $commentGateway
     * @param Request $request
     * @return Response
     */
    public function showReportedComments(
        CommentGateway $commentGateway,
        Request $request
    )
    {
        $reportedComments = $commentGateway->paginatedReportedCommentsList(
            $request->query->getInt('page', 1)
        );

        return $this->render('forum/reported_comments.html.twig', [
            'reported' => $reportedComments
        ]);
    }
}
