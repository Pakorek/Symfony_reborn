<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
  private $passwordHasher;

  public function __construct(UserPasswordHasherInterface $passwordHasher)
  {
    $this->passwordHasher = $passwordHasher;
  }

  public function load(ObjectManager $manager)
  {
    // create new contributor (author)
    $contributor = new User();
    $contributor->setEmail('contributor@monsite.com');
    $contributor->setRoles(['ROLE_CONTRIBUTOR']);
    $contributor->setPassword($this->passwordHasher->hashPassword(
      $contributor,
      'contributorpassword'
    ));

    $manager->persist($contributor);

    // create new admin
    $admin = new User();
    $admin->setEmail('admin@monsite.com');
    $admin->setRoles(['ROLE_ADMIN']);
    $admin->setPassword($this->passwordHasher->hashPassword(
      $admin,
      'adminpassword'
    ));

    $manager->persist($admin);

    // Sauvegarde des 2 nouveaux utilisateurs :
    $manager->flush();
  }
}
