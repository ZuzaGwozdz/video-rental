<?php
/**
 * Tape repository.
 */

namespace App\Repository;

use App\Entity\Tape;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TapeRepository.
 *
 * @method Tape|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tape|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tape[]    findAll()
 * @method Tape[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TapeRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 4;

    /**
     * TapeRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tape::class);
    }

    /**
     * Save record.
     *
     * @param Tape $tape Tape entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Tape $tape): void
    {
        $this->_em->persist($tape);
        $this->_em->flush($tape);
    }

    /**
     * Delete record.
     *
     * @param Tape $tape Tape entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Tape $tape): void
    {
        $this->_em->remove($tape);
        $this->_em->flush($tape);
    }


    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('tape', 'category')
            ->join('tape.category', 'category')
            ->orderBy('tape.updatedAt', 'DESC');
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null)
    {
        return $queryBuilder ?? $this->createQueryBuilder('tape');
    }

    // /**
    //  * @return Tape[] Returns an array of Tape objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tape
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
