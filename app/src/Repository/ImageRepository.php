<?php
/**
 * Image repository.
 */

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Image repository.
 * 
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    /**
     * ImageRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    /**
     * Save record.
     *
     * @param Image $image Image entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Image $image): void
    {
        $this->_em->persist($image);
        $this->_em->flush($image);
    }

    /**
     * Delete record.
     *
     * @param Image $image Image entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Image $image): void
    {
        $this->_em->remove($image);
        $this->_em->flush($image);
    }
}
