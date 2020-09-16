<?php
/**
 * Tape service.
 */

namespace App\Service;

use App\Entity\Tape;
use App\Repository\TapeRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TapeService.
 */
class TapeService
{
    /**
     * Tape repository.
     *
     * @var TapeRepository
     */
    private $tapeRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * Category service.
     *
     * @var CategoryService
     */
    private $categoryService;

    /**
     * Tag service.
     *
     * @var TagService
     */
    private $tagService;

    /**
     * TaskService constructor.
     *
     * @param TapeRepository     $tapeRepository  Task repository
     * @param PaginatorInterface $paginator       Paginator
     * @param CategoryService    $categoryService Category service
     * @param TagService         $tagService      Tag service
     */
    public function __construct(TapeRepository $tapeRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->tapeRepository = $tapeRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Create paginated list.
     *
     * @param int   $page    Page number
     * @param array $filters Filters array
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->tapeRepository->queryAll($filters),
            $page,
            TapeRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save tape.
     *
     * @param Tape $tape Tape entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Tape $tape): void
    {
        $this->tapeRepository->save($tape);
    }

    /**
     * Delete tape.
     *
     * @param Tape $tape Tape entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Tape $tape): void
    {
        $this->tapeRepository->delete($tape);
    }

    /**
     * Prepare filters for the tapes list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category']) && is_numeric($filters['category'])) {
            $category = $this->categoryService->findOneById(
                $filters['category']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (isset($filters['tag']) && is_numeric($filters['tag'])) {
            $tag = $this->tagService->findOneById($filters['tag']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }
}
