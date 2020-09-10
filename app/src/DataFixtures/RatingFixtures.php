<?php
/**
 * Rating fixture.
 */

namespace App\DataFixtures;

use App\Entity\Rating;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class RatingFixtures.
 */
class RatingFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'ratings', function ($i) {
            $rating = new Rating();
            $rating->setNote($this->faker->numberBetween(0,5));
            $rating->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $rating->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $rating->setAuthor($this->getReference(UserFixtures::USER . $i));
            $rating->setTape($this->getRandomReference('tapes'));

            return $rating;
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