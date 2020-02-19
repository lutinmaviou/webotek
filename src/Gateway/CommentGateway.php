<?php

namespace App\Gateway;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentGateway
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CommentRepo
     */
    private $commentRepo;

    public function __construct(
        EntityManagerInterface $em,
        CommentRepository $commentRepo
    ) {
        $this->em = $em;
        $this->commentRepo = $commentRepo;
    }

    public function delete(Comment $comment)
    {
        $this->em->remove($comment);
        $this->em->flush();
    }

    public function show()
    {
        $query = $this->commentRepo->findComments();
    }
}
