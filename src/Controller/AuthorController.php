<?php


namespace App\Controller;


use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @Route("/author/insert", name="author_insert")
     *
     * insertion d'un enregistrement dans la table author
     */
    public function insertAuthor(EntityManagerInterface $entityManager)
    {
        // je crée une instance de l'entité Author
        $author = new Author();
        // j'assigne des valeurs aux différentes propriétés
        // grâce aux setters de l'entité
        $author->setLastname('Nom');
        $author->setFirstname('Prénom');
        $author->setBirthdate(new \DateTime('1945-02-12'));
        $author->setDeathdate(new \DateTime('1945-02-12'));
        $author->setBio('Biographie du nouvel auteur');

        //sauvegarde ~= commit
        $entityManager->persist($author);
        //envoi ~= push
        $entityManager->flush();

        var_dump('Auteur enregistré'); die;
    }

    /**
     * @Route("/author/{id}/delete", name="author_delete")
     *
     * supprime un enregistrement dans la table author
     */
    public function removeAuthor($id, AuthorRepository $authorRepository, EntityManagerInterface $entityManager){
        // je récupère le livre(entité) dont l'id est celui de la wildcard
        $author = $authorRepository->find($id);

        // je supprime l'enregistrement
        $entityManager->remove($author);
        // j'envoie vers la BDD
        $entityManager->flush();

        var_dump('Auteur supprimé'); die;
    }

    /**
     * @Route("/author/{id}/update", name="author_update")
     *
     * modifie un enregistrement dans la table author
     */
    public function updateAuthor($id, AuthorRepository $authorRepository, EntityManagerInterface $entityManager){
        $author = $authorRepository->find($id);

        $author->setLastname('Modif');

        $entityManager->flush();

        var_dump('Auteur mis à jour'); die;
    }

    /**
     * @Route("/author/form/insert", name="author_form_insert")
     */
    public function authorFormInsert(Request $request, EntityManagerInterface $entityManager)
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $formAuthorView = $form->createView();

        // si la méthode est POST // si le formulaire est envoyé
        if ($request->isMethod('post')){
            // le formulaire récupère les données de la requête POST
            $form->handleRequest($request);

            // on enregistre l'entité créée
            $entityManager->persist($author);
            // on envoie la requête vers la bdd
            $entityManager->flush();
        }

        return $this->render('author/author_form_insert.html.twig',
            [
                'formAuthorView' => $formAuthorView
            ]
        );
    }

    /**
     * @Route("/author/form/update/{id}", name="author_form_update")
     */
    public function authorFormUpdate($id, AuthorRepository $authorRepository, Request $request, EntityManagerInterface $entityManager)
    {
        // je récupère toutes les infos de l'auteur
        $author = $authorRepository->find($id);

        $form = $this->createForm(AuthorType::class, $author);
        $formAuthorView = $form->createView();

        // si la méthode est POST // si le formulaire est envoyé
        if ($request->isMethod('post')) {
            // le formulaire récupère les données de la requête POST
            $form->handleRequest($request);

            // pour sécuriser le contenu du form on vérifie les champs avec la méthode isValid
            if ($form->isValid()) {
                // on enregistre l'entité créée
                $entityManager->persist($author);
                // on envoie la requête vers la bdd
                $entityManager->flush();
            }
        }

        return $this->render('author/author_form_insert.html.twig',
            [
                'formAuthorView' => $formAuthorView
            ]
        );
    }
}