<?php
/**
 * Tape repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\Tape;
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
        $this->_em->flush();
    }


    /**
     * Query all records.
     *
     * @param array $filters
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial tape.{id, createdAt, updatedAt, title, availability}',
                'partial category.{id, title}',
                'partial tags.{id, title}'
            )
            ->join('tape.category', 'category')
            ->leftJoin('tape.tags', 'tags')
            ->orderBy('tape.updatedAt', 'DESC');

        $queryBuilder = $this->applyFiltersToList($queryBuilder, $filters);

        return $queryBuilder;
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder $queryBuilder Query builder
     * @param array                      $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        return $queryBuilder;
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
}
