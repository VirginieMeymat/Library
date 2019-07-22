<?php


namespace App\Controller;


use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/book/list", name="book_list")
     *
     * Je passe en parametre la classe "EntityManagerInterface" avec la variable
     * $entityManager, pour que Symfony mette dans la variable une instance de la
     * classe
     */
    public function bookList(BookRepository $bookRepository)
    {
        // j'utilise la méthode findAll du repository pour récupérer tous mes Book
        $books = $bookRepository->findAll();

        return $this->render('book/book_list.html.twig',
            [
                'books' => $books,
                'type' => false
            ]
        );

    }

    /**
     * @Route("/book/show/{id}", name="book_show")
     *
     * Je passe en parametre la classe "EntityManagerInterface" avec la variable
     * $entityManager, pour que Symfony mette dans la variable une instance de la
     * classe
     */
    public function bookShow(BookRepository $bookRepository, $id)
    {
        // j'utilise la méthode find du repository pour récupérer 1 élément de Book
        $book = $bookRepository->find($id);

       /* $author = $book->getAuthor()->getFirstName();
        $author .= " ".$book->getAuthor()->getLastName();*/

        return $this->render('book/book_show.html.twig',
            [
                'book' => $book
               /* 'author' => $author*/
            ]
        );

    }

    /**
     * @Route("/book/type/{type}", name="book_type_list")
     */
    public function bookTypeList(BookRepository $bookRepository, $type)
    {
        // j'utilise la méthode find du repository pour récupérer 1 élément de Book
        $books = $bookRepository->findByType($type);

        return $this->render('book/book_list.html.twig',
            [
                'books' => $books,
                'type' => $type
            ]
        );

    }


}