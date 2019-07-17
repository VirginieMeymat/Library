<?php


namespace App\Controller;


use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author/list", name="author_list")
     */

    public function listAuhtor(AuthorRepository $authorRepository){
        $authors = $authorRepository->findAll();
        return $this->render('author/author_list.html.twig',
            [
                'authors' => $authors,
                'name' => false
            ]
        );
    }

    /**
     * @Route("/author/show/{id}", name="author_show")
     *
     * Je passe en parametre la classe "EntityManagerInterface" avec la variable
     * $entityManager, pour que Symfony mette dans la variable une instance de la
     * classe
     */
    public function authorShow(AuthorRepository $authorRepository, $id)
    {
        // j'utilise la méthode find du repository pour récupérer 1 élément de Author
        $author = $authorRepository->find($id);

        return $this->render('author/author_show.html.twig',
            [
                'author' => $author
            ]
        );
    }

    /**
     * @Route("/author/name", name="author_name_list")
     */
    public function authorNameList(AuthorRepository $authorRepository, Request $request)
    {
        $name = $request->query->get('search');
        // j'utilise la méthode find du repository pour récupérer 1 élément de Book
        $authors = $authorRepository->findByName($name);

        return $this->render('author/author_list.html.twig',
            [
                'authors' => $authors,
                'name' => $name
            ]
        );

    }
}