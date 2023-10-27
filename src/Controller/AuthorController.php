<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class AuthorController extends AbstractController
{
    #[Route('/author/{name}', name: 'app_author')]
    public function index($name): Response
    {
        $nickname='Mr ' .$name;
        $age=55+25;
        return $this->render('author/index.html.twig', [
            'name' => $nickname,
            'age' => $age,
        ]);
    }

    #[Route('/listAuthor', name: 'list_author')]
    public function listAuthor(AuthorRepository $authrepo): Response
    {
        $authors = $authrepo->findAll();
        return $this->render('author/list.html.twig', [
            'authors' => $authors
        ]);
    }

    #[Route('/authors/{id}', name: 'showauthor')]
    public function showAuthor($id, AuthorRepository $repo): Response
    {
        $author=$repo->find($id);
        return $this->render('author/detailAuthor.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/deleteAuthor/{id}', name: 'deleteauthor')]
    public function deleteAuthor($id, AuthorRepository $repo, EntityManagerInterface $em): Response
    {
        $author=$repo->find($id);
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('list_author');

    }

    #[Route('/Authors/add', name: 'addauthor')]
    public function addAuthor(ManagerRegistry $doctrine, Request $request): Response
    {
            $author = New Author ;
            // Formulaire
            $form=$this->createForm(AuthorType::class,  $author);
            $form->add('Ajouter', SubmitType::class);
            $form->handleRequest($request);
            $em=$doctrine->getManager();
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($author);        
                $em->flush();
                return $this->redirectToRoute('list_author');
            }
        return $this->render('author/addAuthor.html.twig' , [
            'formA' => $form->createView(),
        ]);

    }


    #[Route('/Authors/update/{id}', name: 'updateauthor')]
    public function updateAuthor($id, ManagerRegistry $doctrine, Request $request): Response
    {
            $repo = $doctrine->getRepository(Author::class);
            $author= $repo->find($id);

            
            $form=$this->createForm(AuthorType::class,  $author);
            $form->handleRequest($request);
            $form->add('Modifier', SubmitType::class);
            $em=$doctrine->getManager();
            if ($form->isSubmitted() && $form->isValid()) {       
                $em->flush();
                return $this->redirectToRoute('list_author');
            }
        return $this->render('author/addAuthor.html.twig' , [
            'formA' => $form->createView(),
        ]);

    }



}
