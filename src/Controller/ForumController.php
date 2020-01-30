<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Forum;
use App\Form\NewForumType;
use Doctrine\ORM\EntityManagerInterface;


class ForumController extends AbstractController
{
    /**
     * @Route("/forums", name="app_forums")
     */
    public function createForum(EntityManagerInterface $em, Request $request, SluggerInterface $slugger)
    {
        $forum = new Forum();
        $form = $this->createForm(NewForumType::class, $forum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $topic = $forum->getTopic();
            // Convert to an Url format
            $slugger = new AsciiSlugger('fr');
            $slug = $slugger->slug($topic);
            $forum->setSlug($slug);
            $forum->setCreationDate(new \DateTime());
            //$forum->setAuthor();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'Nouveau forum créé avec succès !');
            //return $this->redirectToRoute('app_forums');
        }
        $repo = $this->getDoctrine()->getRepository(forum::class);
        $forums = $repo->findAll();

        return $this->render('forum/index.html.twig', [
            'new_forum_form' => $form->createView(),
            'forums' => $forums
        ]);
    }

    /**
     * @Route("/forum/{slug}", name="app_forum_display")
     */
    public function displayForum(string $slug)
    {
        $repo = $this->getDoctrine()->getRepository(forum::class);
        $forum = $repo->findOneBy(['slug' => $slug]);
        //dump($topic);

        return $this->render('forum/forum.html.twig', [
            'forum' => $forum
        ]);
    }
}
