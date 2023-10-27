<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/ListBook', name: 'ListBook')]
    public function ListBook(BookRepository $repo): Response
    {
        $books=$repo->findAll();
        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/AddBook', name: 'AddBook')]
    public function AddBook(Request $request, EntityManagerInterface $em): Response
    {
        $book= new Book;
        $form=$this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $book->setPublisher(true);
            $author = $book->getAuthor();
            if($author instanceof Author){
                $author->setNbBooks($author->getNbBooks()+1);
            }

            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("ListBook");
        }
        return $this->render('book/addbook.html.twig', [
            'formB' => $form->createView(),
        ]);
    }

    #[Route('/AfficheBook', name: 'app_AfficheBook')]
    public function Affiche(BookRepository $repository): Response
    {
        $publishedBooks = $repository->findBy(['publisher' => true]);
        $numPublishedBooks = count($publishedBooks);
        $numUnPublishedBooks = $repository->count(['publisher' => false]);

        if ($numPublishedBooks > 0) {
            return $this->render('book/Affiche.html.twig', [
                'publishedBooks' => $publishedBooks,
                'numPublishedBooks' => $numPublishedBooks,
                'numUnPublishedBooks' => $numUnPublishedBooks,
            ]);
        } else {
            return $this->render('book/no_book_found.html.twig');
        }
    }


    #[Route('/editbook/{ref}', name: 'app_editBook')]
    public function edit(BookRepository $repository, $ref, Request $request, EntityManagerInterface $em): Response
    {
        $book = $repository->find($ref);

        if (!$book) {
            throw $this->createNotFoundException('Le livre n\'a pas été trouvé.');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->$em->flush();
            return $this->redirectToRoute("app_AfficheBook");
        }

        return $this->render('book/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/deletebook/{ref}', name: 'app_deleteBook')]
    public function delete($ref, BookRepository $repository, EntityManagerInterface $em)
    {
        $book = $repository->find($ref);
        $em->remove($book);
        $em->flush();


        return $this->redirectToRoute('app_AfficheBook');
    }
    #[Route('/ShowBook/{ref}', name: 'app_detailBook')]

    public function showBook($ref, BookRepository $repository)
    {
        $book = $repository->find($ref);
        if (!$book) {
            return $this->redirectToRoute('app_AfficheBook');
        }

        return $this->render('book/show.html.twig', [
            'b' => $book
        ]);

}
    
}