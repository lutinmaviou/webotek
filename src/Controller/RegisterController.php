<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            //dd($data);
            //dump($data);
            $user = new User();
            $user->getFirstName();
            $user->getLastName();
            $user->getPseudo();
            $user->getEmail();
            $user->getRoles();
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            dump($password);
            $user->setPassword($password);
            $user->getPassword();
            dump($user);
            //$em->persist($data);
            //$em->flush();
            dump($user);
        }

        return $this->render('form/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}

/*
   
public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
{
    // 1) Build the form
    $user = new User();
    $form = $this->createForm(RegisterType::class, $user);

    // 2) Handle the submit (will only happen on POST)
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        // 3) Encode the password
        $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        // Save the User
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        //return $this->redirectToRoute('/');
    }
    return $this->render('form/register.html.twig', [
        'registerForm' => $form->createView()
    ]);
}
*/
