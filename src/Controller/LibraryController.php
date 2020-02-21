<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    /**
     * @Route("/", name="app_home")
     */
    public function home()
    {
        return $this->render('library/home.html.twig');
    }

    /**
     * @Route("/legals", name="app_legals")
     */
    public function showLegals(){
        return $this->render('library/legals.html.twig');
    }
}
