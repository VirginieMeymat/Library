<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findByName($string)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.lastname LIKE :name')
            ->orWhere('a.firstname LIKE :name')
            ->setParameter('name', '%'.$string.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByLastname($lastname)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.lastname LIKE :name')
            ->setParameter('name', '%'.$lastname.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    /* méthode pour trouver des auteurs en fonction d'un mot de leur biographie */
    public function getAuthorsByWord(){
        $word = 'livre';
        $qb = $this->createQueryBuilder('a');

        $query = $qb->select('a')
            ->where('a.bio LIKE :word')
            ->setParameter('word', '%'.$word.'%')
            ->getQuery();
        $resultats = $query->getArrayResult();

        return $resultats;

    }
    // /**
    //  * @return Author[] Returns an array of Author objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Author
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
