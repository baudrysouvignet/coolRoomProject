<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\UserCollection;
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

        $userArray = [];
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email)
                ->setPassword(password_hash('password', PASSWORD_BCRYPT));

            if (rand(0, 1)) {
                $user->setPseudo($this->faker->userName);
            }

            $userArray[] = $user;
            $manager->persist($user);
        }

        // Category
        $categoryArray = [];
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setTitle($this->faker->sentence(3))
                ->setDescription($this->faker->paragraph(3))
                ->setImage('IMG-0703-65cb6f5e7a805.jpg');

            $categoryArray[] = $category;
            $manager->persist($category);
        }

        // Product
        $productArray = [];
        for ($i = 0; $i < 12; $i++) {
            $product = new Product();
            $product->setTitle($this->faker->sentence(3))
                ->setDescription($this->faker->paragraph(3))
                ->setImage('IMG-0703-65cb6f5e7a805.jpg')
                ->setCategory($categoryArray[rand(0, 4)]);

            $productArray[] = $product;
            $manager->persist($product);
        }

        // Reiew
        for ($i = 0; $i < 5; $i++) {
            $review = new Review();
            $review->setNote(rand(1, 5))
                ->setDescription($this->faker->paragraph(3))
                ->setUserReview($userArray[rand(0, 2)])
                ->setPriductReview($productArray[rand(0, 4)])
                ->setModerated('suspended');

            $manager->persist($review);
        }

        // Collection
        for ($i = 0; $i < 5; $i++) {
            $collection = new UserCollection();
            $collection->setName($this->faker->sentence(3))
                ->setUser($userArray[rand(0, 2)]);

            for ($j = 0; $j < rand(1, 5); $j++) {
                $collection->addProduct($productArray[rand(0, 4)]);
            }

            $manager->persist($collection);
        }

        $manager->flush();
    }
}
