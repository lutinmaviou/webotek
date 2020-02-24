<?php

namespace App\Controller;

use App\Gateway\CommentGateway;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    /**
     * @Route("/", name="app_home")
     * @param CommentGateway $commentGateway
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(
        CommentGateway $commentGateway,
        Request $request
    )
    {
        $reportedComments = $commentGateway->paginatedReportedCommentsList(
            $request->query->getInt('page', 1)
        );

        return $this->render('library/home.html.twig', [
            'reported' => $reportedComments
        ]);
    }

    /**
     * @Route("/legals", name="app_legals")
     */
    public function showLegals()
    {
        return $this->render('library/legals.html.twig');
    }
}
