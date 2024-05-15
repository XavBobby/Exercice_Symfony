<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorNameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/add', name: 'author_add')]
        public function addAuthor(Request $request, ManagerRegistry $doctrine): Response
    {
        $category = new Author();
        $form = $this->createForm(AuthorNameType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('admin/author/add.html.twig', [
            'form' => $form->createView(),
        ]);
        
    }
        
}
