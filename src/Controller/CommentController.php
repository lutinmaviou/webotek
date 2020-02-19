<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Gateway\CommentGateway;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/forum/delete/{slug}/{commentId}", name="app_forum_remove_comment", methods="DELETE")
     */
    public function removeComment(
        int $commentId,
        string $slug,
        Request $request,
        CommentGateway $commentGateway,
        CommentRepository $commentRepo
    ) {
        $comment = $commentRepo->find($commentId);
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->get('_token'))) {
            $commentGateway->delete($comment);
            $this->addFlash('success', 'Message supprimé avec succès !');
        }

        return $this->redirectToRoute('app_forum_display', [
            'slug' => $slug,
        ]);
    }
}
