<?php

namespace App\Controller;

use App\Gateway\CommentGateway;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    /**
     * @Route("/", name="app_home")
     * @param CommentGateway $commentGateway
     * @param Request $request
     * @return Response
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
     * @param CommentGateway $commentGateway
     * @param Request $request
     * @return Response
     */
    public function showLegals(
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
        return $this->render('library/legals.html.twig');
    }
}
