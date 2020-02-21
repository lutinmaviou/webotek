<?php

namespace App\Gateway;

use App\Entity\Forum;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\String\Slugger\SluggerInterface;

class ForumGateway
{

    private const FORUMS_PER_PAGE = 8;

    /**
     * @var ForumRepository
     */
    private $forumRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * @var PaginatorInterface
     */
    private $paginator;


    public function __construct(
        PaginatorInterface $paginator,
        ForumRepository $forumRepo,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ) {
        $this->paginator = $paginator;
        $this->forumRepository = $forumRepo;
        $this->slugger = $slugger;
        $this->em = $em;
    }

    public function save(Forum $forum)
    {
        if (!$forum->getSlug()) {
            $forum->setSlug(strtolower($this->slugger->slug($forum->getTopic())));
        }

        $this->em->persist($forum);
        $this->em->flush();
    }

    /**
     * @param $page
     * @return PaginationInterface
     */
    public function paginatedListForums($page)
    {
        $query = $this->forumRepository->findAllQuery();
        return $this->paginator->paginate(
            $query,
            $page,
            self::FORUMS_PER_PAGE
        );
    }
}
