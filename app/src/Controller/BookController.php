<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/book.html.twig', [
            'post' => [
                'title' => 'Le titre',
                'content' => 'Le super contenu'
            ],
        ]);
    }



    #[Route('/book/add', name: 'book_add')]
    public function addBook(Request $request, ManagerRegistry $doctrine): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authorId = $form->get('authorId')->getData();
            $author = $doctrine->getRepository(Author::class)->find($authorId);

            // Asssocier l'auteur au livre
            $book->setAuthorId($author);

            // Récupérer l'EntityManager
            $entityManager = $doctrine->getManager();

            // Sauvegarder le livre dans la base de données
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

