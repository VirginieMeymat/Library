<?php


namespace App\Controller\Admin;


use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookAdminController extends AbstractController
{
    /**
     * @Route("/admin/book/list", name="book_list_admin")
     *
     * Je passe en parametre la classe "EntityManagerInterface" avec la variable
     * $entityManager, pour que Symfony mette dans la variable une instance de la
     * classe
     */
    public function bookListAdmin(BookRepository $bookRepository)
    {
        // j'utilise la méthode findAll du repository pour récupérer tous mes Book
        $books = $bookRepository->findAll();

        return $this->render('book/book_list_admin.html.twig',
            [
                'books' => $books,
                'type' => false
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

    /**
     * @Route("/admin/book/insert", name="book_form_insert")
     */
    public function bookFormInsert(Request $request, EntityManagerInterface $entityManager){
        // je crée une instance de la classe Book
        $book = new Book();
        // je crée un nouveau formulaire pour l'entité Book
        $form = $this->createForm(BookType::class, $book);
        // je crée une vue du formulaire
        $formBookView = $form->createView();

        if ($request->isMethod('post')) {
            // je récupère les données du form
            $form->handleRequest($request);

            if ($form->isValid()) {
                // on enregistre l'entité créée
                $entityManager->persist($book);
                // on envoie la requête vers la bdd
                $entityManager->flush();
            }
            return $this->redirectToRoute('book_list_admin');
        } else {
            return $this->render('book/book_form.html.twig',
                [
                    'formBookView' => $formBookView,
                    'title_page' => 'Nouveau livre'
                ]
            );
        }
    }

    /**
     * @Route("/admin/book/update/{id}", name="book_form_update")
     */
    public function bookFormUpdate($id, BookRepository $bookRepository, Request $request, EntityManagerInterface $entityManager){
        // je crée une instance de la classe Book
        $book = $bookRepository->find($id);
        // je crée un nouveau formulaire pour l'entité Book
        $form = $this->createForm(BookType::class, $book);
        // je crée une vue du formulaire
        $formBookView = $form->createView();

        if ($request->isMethod('post')) {
            // je récupère les données du form
            $form->handleRequest($request);

            if ($form->isValid()) {
                // on enregistre l'entité créée
                $entityManager->persist($book);
                // on envoie la requête vers la bdd
                $entityManager->flush();
            }
            return $this->redirectToRoute('book_list_admin');
        } else {
            return $this->render('book/book_form.html.twig',
                [
                    'formBookView' => $formBookView,
                    'title_page' => 'Modification du livre'
                ]
            );
        }
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

        return $this->redirectToRoute('author_list_admin');
    }
}