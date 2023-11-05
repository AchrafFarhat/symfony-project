<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function ShowAllBooksByAuthor($id)
    {
        return $this->createQueryBuilder('a')
        ->join('a.author','b')
        ->addSelect('b')
        ->where('b.id = :id')
        ->setParameter('id',$id)
        ->getQuery()->getResult();
    }

    public function findByDate(int $year, int $minBooks)
    {
        return $this->createQueryBuilder('b')
        ->join('b.author', 'a')
        ->where('b.publicationdate < :year')
        ->andWhere('a.nb_books > :minAuthorBooks')
        ->setParameters([
            'year' => $year,
            'minAuthorBooks' => $minBooks,
        ])
        ->getQuery()
        ->getResult();
    }

    public function findByCategory($category)
{
    return $this->createQueryBuilder('b')
        ->join('b.author', 'a')
        ->select('SUM(a.nb_books) as totalQuantity')
        ->where('b.category = :category')
        ->setParameter('category', $category)
        ->getQuery()
        ->getSingleScalarResult();
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
