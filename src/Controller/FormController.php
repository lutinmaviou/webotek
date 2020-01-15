<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            //dd($data);
            dump($data);
            $user = new User();
            $user->getFirstName();
            $user->getLastName();
            $user->getPseudo();
            $user->getEmail();
            $user->getRole();
            $user->getPassword();
            //dump($user);
            $em->persist($data);
            $em->flush();
        }

        return $this->render('form/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
