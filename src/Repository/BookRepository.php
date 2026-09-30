<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
    public function findByTitleOrAuthor(?string $titleQuery, ?string $authorQuery): array
    {
        $qb = $this->createQueryBuilder('b');

        $orX = $qb->expr()->orX();

        if ($titleQuery !== null && $titleQuery !== '') {
            $orX->add($qb->expr()->like('b.title', ':title'));
            $qb->setParameter('title', '%' . $titleQuery . '%');
        }
        if ($authorQuery !== null && $titleQuery !== '') {
            $orX->add($qb->expr()->like('b.author', ':author'));
            $qb->setParameter('author', '%' . $authorQuery . '%');
        }

        if ($orX->count() > 0) {
            $qb->where($orX);
        }
        return $qb->getQuery()->getResult();
    }
    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
