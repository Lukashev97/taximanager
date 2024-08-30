<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Model;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandModelFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    // Define brands and models
    $brands = [
      ['name' => 'BMW', 'models' => ['X6', 'X5']],
      ['name' => 'Audi', 'models' => ['A4', 'Q7']],
      ['name' => 'Toyota', 'models' => ['Corolla', 'Camry']],
    ];

    foreach ($brands as $brandData) {
      $brand = new Brand();
      $brand->setName($brandData['name']);

      $manager->persist($brand);

      foreach ($brandData['models'] as $modelName) {
        $model = new Model();
        $model->setName($modelName);
        $model->setBrand($brand);

        $manager->persist($model);
      }
    }

    $manager->flush();
  }
}
