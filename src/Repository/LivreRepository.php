<?php

namespace App\Repository;

use App\DTO\Payment;
use App\Entity\Livre;
use App\DTO\SearchLivreCriteria;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function findLast(int $number = 10): array
    {
        return $this->createQueryBuilder('livre')
            ->orderBy('livre.dateMiseAJour', 'DESC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
    }

    public function findCriteria(SearchLivreCriteria $criteria): array
    {
        $queryBuilder = $this
            ->createQueryBuilder('livre')
            ->setFirstResult($criteria->limit * ($criteria->page - 1))
            ->setMaxResults($criteria->limit)
            ->orderBy("livre.{$criteria->orderBy}", $criteria->direction);

        if ($criteria->titre) {
            $queryBuilder = $queryBuilder
                ->andWhere('livre.titre LIKE :titre')
                ->setParameter('titre', "%{$criteria->titre}%");
        }

        if ($criteria->minPrix) {
            $queryBuilder = $queryBuilder
                ->andWhere('livre.prix >= :minPrix')
                ->setParameter('minPrix', $criteria->minPrix);
        }
        if ($criteria->maxPrix) {
            $queryBuilder = $queryBuilder
                ->andWhere('livre.prix <= :maxPrix')
                ->setParameter('maxPrix', $criteria->minPrix);
        }
        if ($criteria->auteur) {
            $queryBuilder = $queryBuilder
                ->leftJoin('livre.auteur', 'auteur')
                ->andWhere('CONCAT(auteur.prenom, CONCAT(\'\', auteur.nom)) LIKE :auteur')
                ->setParameter('auteur', "%{$criteria->auteur}%");
        }
        if ($criteria->categorie) {
            $queryBuilder = $queryBuilder
                ->leftJoin('livre.categorie', 'category')
                ->andWhere('categorie.titre LIKE :categorie')
                ->setParameter('categorie', "%{$criteria->categorie}%");
        }
        if ($criteria->revendeur) {
            $queryBuilder = $queryBuilder
                ->leftJoin('livre.revendeur', 'revendeur')
                ->andWhere('revendeur.email LIKE :revendeur')
                ->setParameter('revendeur', "%{$criteria->revendeur}%");
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findOneById($id): Livre
    {
        return $this->createQueryBuilder('livre')
            ->andWhere('livre.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
