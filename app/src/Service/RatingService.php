<?php
/**
 * Rating service.
 */

namespace App\Service;

use App\Entity\Rating;
use App\Repository\RatingRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RatingService.
 */
class RatingService
{
    /**
     * Rating repository.
     *
     * @var RatingRepository
     */
    private $ratingRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * RatingService constructor.
     *
     * @param RatingRepository   $ratingRepository Rating repository
     * @param PaginatorInterface $paginator        Paginator
     */
    public function __construct(RatingRepository $ratingRepository, PaginatorInterface $paginator)
    {
        $this->ratingRepository = $ratingRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->ratingRepository->queryAll(),
            $page,
            RatingRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save rating.
     *
     * @param Rating $rating Rating entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Rating $rating): void
    {
        $this->ratingRepository->save($rating);
    }

    /**
     * Delete rating.
     *
     * @param Rating $rating Rating entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Rating $rating): void
    {
        $this->ratingRepository->delete($rating);
    }

    /**
     * Find rating by Id.
     *
     * @param int $id Rating Id
     *
     * @return \App\Entity\Rating|null Rating entity
     */
    public function findOneById(int $id): ?Rating
    {
        return $this->ratingRepository->findOneById($id);
    }

    /**
     * Find ratings by.
     *
     * @param array $criteria Criteria
     *
     * @return \App\Entity\Rating[] Rating collection
     */
    public function findOneBy(array $criteria)
    {
        return $this->ratingRepository->findBy($criteria);
    }

    /**
     * Find ratings by.
     *
     * @param array $criteria Criteria
     *
     * @return \App\Entity\Rating[] Rating collection
     */
    public function findBy(array $criteria)
    {
        return $this->ratingRepository->findBy($criteria);
    }
}
