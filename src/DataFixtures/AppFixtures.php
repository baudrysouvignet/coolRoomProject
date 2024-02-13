<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        // User
        $user = new User();
        $user->setEmail('baud@baud.com')->setPassword(password_hash('password', PASSWORD_BCRYPT))->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email)
                ->setPassword(password_hash('password', PASSWORD_BCRYPT));

            if (rand(0, 1)) {
                $user->setPseudo($this->faker->userName);
            }

            $manager->persist($user);
        }

        // Category
        // for ($i = 0; $i < 5; $i++) {
        //     $category = new Category();
        //     $category->setTitle($this->faker->sentence(3))
        //         ->setDescription($this->faker->paragraph(3))
        //         ->setImage('https://picsum.photos/200/300');
        //     $manager->persist($category);
        // }

        $manager->flush();
    }
}
