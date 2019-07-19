<?php


namespace App\Controller;


use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    /**
     * @Route("/book/insert", name="book_insert")
     *
     * insertion d'un enregistrement dans la table book
     */
    public function insertBook(EntityManagerInterface $entityManager, AuthorRepository $authorRepository)
    {
        // je récupère un auteur en fonction de son id
        $author = $authorRepository->find(5);

        // je crée une instance de l'entité Book
        $book = new Book();
        // j'assigne des valeurs aux différentes propriétés
        // grâce aux setters de l'entité
        $book->setTitle('Nouveau livre');
        $book->setType('Fantastique');
        $book->setNbPages(412);
        $book->setSummary('Résumé du nouveau livre');
        $book->setAuthor($author);

        // Cette méthode signale à Doctrine que l'objet doit être enregistré
        //sauvegarde ~= commit
         $entityManager->persist($book);
         //envoi ~= push
        $entityManager->flush();

        var_dump('Livre enregistré'); die;
    }

    /**
     * @Route("/book/{id}/delete", name="book_delete")
     *
     * supprime un enregistrement dans la table book
     */
    public function removeBook($id, BookRepository $bookRepository, EntityManagerInterface $entityManager){
        // je récupère le livre(entité) dont l'id est celui de la wildcard
        $book = $bookRepository->find($id);

        // Signale à Doctrine qu'on veut supprimer l'entité en argument de la base de données
        $entityManager->remove($book);
        // Met à jour la base à partir des objets signalés à Doctrine.
        // Tant que cette méthode n'est pas appellée, rien n'est modifié en base.
        $entityManager->flush();

        var_dump('Livre supprimé'); die;
    }

    /**
     * @Route("/book/{id}/update", name="book_update")
     *
     * modifie un enregistrement dans la table book
     */
    public function updateBook($id, BookRepository $bookRepository, EntityManagerInterface $entityManager, AuthorRepository $authorRepository){
        $author = $authorRepository->find(1);

        // je récupère le livre(entité) dont l'id est celui de la wildcard
        $book = $bookRepository->find($id);

        // je modifie l'enregistrement
        $book->setAuthor($author);


        // j'envoie vers la BDD
        $entityManager->flush();

        var_dump('Livre mis à jour'); die;
    }
}