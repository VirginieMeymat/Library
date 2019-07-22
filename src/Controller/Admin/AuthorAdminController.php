<?php


namespace App\Controller\Admin;


use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorAdminController extends AbstractController
{

    /**
     * @Route("/admin/author/list", name="author_list_admin")
     */

    public function listAuhtorAmin(AuthorRepository $authorRepository){
        $authors = $authorRepository->findAll();
        return $this->render('author/author_list_admin.html.twig',
            [
                'authors' => $authors,
                'name' => false
            ]
        );
    }

    /**
     * @Route("/admin/author/insert", name="author_form_insert")
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

            // pour sécuriser le contenu du form on vérifie les champs avec la méthode isValid
            if ($form->isValid()) {
                // on enregistre l'entité créée
                $entityManager->persist($author);
                // on envoie la requête vers la bdd
                $entityManager->flush();
            }
            return $this->redirectToRoute('author_list_admin');
        } else {
            return $this->render('author/author_form.html.twig',
                [
                    'formAuthorView' => $formAuthorView,
                    'title_page' => 'Nouvel auteur'
                ]
            );
        }
    }

    /**
     * @Route("/admin/author/update/{id}", name="author_form_update")
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
            // je redirige vers la route qui affiche la liste des auteurs
            return $this->redirectToRoute('author_list_admin');
        } else {
            // sinon j'affiche le formulaire prérempli
            return $this->render('author/author_form.html.twig',
                [
                    'formAuthorView' => $formAuthorView,
                    'title_page' => 'Modification de l\'auteur'
                ]
            );
        }
    }

    /**
     * @Route("/admin/author/{id}/delete", name="author_delete")
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

        return $this->redirectToRoute('author_list_admin');
    }
}