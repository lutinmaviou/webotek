<?php

namespace App\Gateway;

use App\Entity\Forum;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface as PaginationInterfaceAlias;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\String\Slugger\SluggerInterface;

class ForumGateway
{

    const FORUMS_PER_PAGE = 8;

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


    public function __construct(
        PaginatorInterface $paginator,
        ForumRepository $forumRepository,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ) {
        $this->paginator = $paginator;
        $this->forumRepository = $forumRepository;
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
     * @return PaginationInterfaceAlias
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
