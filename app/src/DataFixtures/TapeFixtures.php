<?php
/**
 * Tape fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tape;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TapeFixtures.
 */
class TapeFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'tapes', function ($i) {
            $tape = new Tape();
            $tape->setTitle($this->faker->sentence(3));
            $tape->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $tape->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $tape->setCategory($this->getRandomReference('categories'));
            $tape->setAvailability($this->faker->boolean);

            return $tape;
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
        return [CategoryFixtures::class];
    }
}
