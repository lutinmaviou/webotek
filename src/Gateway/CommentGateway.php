<?php

namespace App\Gateway;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CommentGateway
{
    private const COMMENTS_PER_PAGE = 5;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(
        PaginatorInterface $paginator,
        EntityManagerInterface $em,
        CommentRepository $commentRepo
    ) {
        $this->paginator = $paginator;
        $this->em = $em;
        $this->commentRepository = $commentRepo;
    }

    public function delete(Comment $comment)
    {
        $this->em->remove($comment);
        $this->em->flush();
    }

    public function save(Comment $comment)
    {
        $this->em->persist($comment);
        $this->em->flush();
    }

    /**
     * @param $forum
     * @param $page
     * @return PaginationInterface
     */
    public function paginatedCommentsList($forum, $page)
    {
        $query = $this->commentRepository->findAllByForum($forum);
        return $this->paginator->paginate(
            $query,
            $page,
            self::COMMENTS_PER_PAGE
        );
    }

    /**
     * @param $page
     * @return PaginationInterface
     */
    public function paginatedReportedCommentsList($page)
    {
        $query = $this->commentRepository->findReportedComments();
        return $this->paginator->paginate(
            $query,
            $page,
            self::COMMENTS_PER_PAGE
        );
    }
}
