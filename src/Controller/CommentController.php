<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * TODO à deplacer dans un CommentController
     * 
     * @Route("/forum/delete/{slug}/{commentId}", name="app_forum_remove_comment", methods="DELETE")
     */
    public function removeComment(int $commentId, string $slug, EntityManagerInterface $em, Request $request)
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commentId);
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
        }
        return $this->redirectToRoute('app_forum_display', [
            'slug' => $slug,
        ]);
    }
}
