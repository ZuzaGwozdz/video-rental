<?php
/**
 * Reservation service.
 */

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ReservationService.
 */
class ReservationService
{
    /**
     * Reservation repository.
     *
     * @var ReservationRepository
     */
    private $reservationRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * ReservationService constructor.
     *
     * @param ReservationRepository $reservationRepository Reservation repository
     * @param PaginatorInterface    $paginator             Paginator
     */
    public function __construct(ReservationRepository $reservationRepository, PaginatorInterface $paginator)
    {
        $this->reservationRepository = $reservationRepository;
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
            $this->reservationRepository->queryAll(),
            $page,
            ReservationRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Create paginated list by author.
     *
     * @param int           $page Page number
     * @param UserInterface $user
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedListByAuthor(int $page, UserInterface $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->reservationRepository->queryByAuthor($user),
            $page,
            ReservationRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save reservation.
     *
     * @param Reservation $reservation Reservation entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Reservation $reservation): void
    {
        $this->reservationRepository->save($reservation);
    }

    /**
     * Delete reservation.
     *
     * @param Reservation $reservation Reservation entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Reservation $reservation): void
    {
        $this->reservationRepository->delete($reservation);
    }

    /**
     * Find reservation by Id.
     *
     * @param int $id Reservation Id
     *
     * @return \App\Entity\Reservation|null Reservation entity
     */
    public function findOneById(int $id): ?Reservation
    {
        return $this->reservationRepository->findOneById($id);
    }

    /**
     * Find reservations by.
     *
     * @param array $criteria Criteria
     *
     * @return \App\Entity\Reservation[] Reservation collection
     */
    public function findBy(array $criteria)
    {
        return $this->reservationRepository->findBy($criteria);
    }
}
