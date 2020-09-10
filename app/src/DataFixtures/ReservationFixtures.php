<?php
/**
 * Reservation fixture.
 */

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ReservationFixtures.
 */
class ReservationFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'reservations', function ($i) {
            $reservation = new Reservation();
            $reservation->setComment($this->faker->text(100));
            $reservation->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $reservation->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $reservation->setAuthor($this->getReference(UserFixtures::USER . $i));
            $reservation->setTape($this->getRandomReference('tapes'));
            $reservation->setStatus($this->faker->boolean);

            return $reservation;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class, TapeFixtures::class];
    }
}