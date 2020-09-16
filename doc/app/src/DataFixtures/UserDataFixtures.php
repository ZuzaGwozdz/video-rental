<?php
/**
 * UserData fixture.
 */

namespace App\DataFixtures;

use App\Entity\UserData;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserDataFixtures.
 */
class UserDataFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'users_data', function ($i) {
            $userData = new UserData();
            $userData->setName($this->faker->firstName());
            $userData->setSurname($this->faker->lastName());
            $userData->setBirthday($this->faker->dateTimeBetween('-60 years', '-1 days'));
            $userData->setUser($this->getReference(UserFixtures::USER.$i));
            $userData->setBlocked($this->faker->boolean);

            return $userData;
        });

        $this->createMany(3, 'admins_data', function ($i) {
            $userData = new UserData();
            $userData->setName($this->faker->firstName());
            $userData->setSurname($this->faker->lastName());
            $userData->setBirthday($this->faker->dateTimeBetween('-60 years', '-1 days'));
            $userData->setUser($this->getReference(UserFixtures::ADMIN.$i));
            $userData->setBlocked($this->faker->boolean);

            return $userData;
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
        return [UserFixtures::class];
    }
}
