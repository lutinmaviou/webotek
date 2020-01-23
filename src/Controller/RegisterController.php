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
     * @Route("/sign-in", name="app_sign_in")
     */
    public function signIn(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$user->getRoles();
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            //$user->getPassword();
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'Votre compte à bien été enregistré.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('form/sign_in.html.twig', [
            'sign_in_form' => $form->createView()
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
