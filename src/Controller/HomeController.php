<?php


namespace App\Controller;


use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function getLastElements(BookRepository $bookRepository, AuthorRepository $authorRepository)
    {
        // j'utilise la méthode find du repository pour récupérer 1 élément de Book
        $books = array_slice($bookRepository->findAll(),-2);
        $authors = array_slice($authorRepository->findAll(),-2);

        return $this->render('home.html.twig',
            [
                'books' => $books,
                'authors' => $authors
            ]
        );

    }

    /**
     * @Route("/contact", name="contact")
     *
     * retourne page html compilée du fichier twig 'contact'
     * avec pour paramètre le tableau d'éléments des profils pour la sidebar
     */
    public function contactForm(){

        return $this->render('contact.html.twig');
    }
}