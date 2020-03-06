<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use phpDocumentor\Reflection\Types\Array_;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param $forumId
     * @return array
     */
    public function findAllByForum($forumId): array
    {
        return $this->createQueryBuilder('c')
            ->where("c.forum = {$forumId}")
            ->orderBy('c.creationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findReportedComments(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.status = 1')
            ->orderBy('c.creationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
