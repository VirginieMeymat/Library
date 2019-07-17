<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findByType($type)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult()
            ;
    }

    /* fontion identique à la précédente // version David Robert */
    public function findByGenre()
    {
        $style = 'Policier';
        // je récupère le query builder de Doctrine pour créer la requête
        $qb = $this->createQueryBuilder('b');
        // je viens sélectionner tous les éléments
        // de la table Book
        $query = $qb->select('b')
            // je fais ma conditions WHERE
            // je lui demande de récupérer uniquement
            // les livres dont la colonne style correspond
            // à la valeur de la variable $style
            ->where('b.type = :style')
            // j'utilise les parametres pour sécuriser la variable
            // $style et éviter les attaques
            ->setParameter('style', $style)
            // je créé la requete SQL équivalente
            ->getQuery();
        // je récupère les résultats sous forme d'array
        $resultats = $query->getArrayResult();
        // je retourne les résultats
        return $resultats;
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
