<?php

namespace App\Repository;

use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Program::class);
  }

  /**
   * @param $value
   * @return Program[] Returns an array of Program objects
   */
  public function findLikeName($value)
  {
    return $this->createQueryBuilder('p')
      ->andWhere('p.title LIKE :name')
      ->orWhere('a.name LIKE :name')
      ->join('p.actors', 'a')
      ->setParameter('name', '%' . $value . '%')
      ->orderBy('p.title', 'ASC')
      ->getQuery()
      ->getResult();
  }

  /*
  public function findOneBySomeField($value): ?Program
  {
      return $this->createQueryBuilder('p')
          ->andWhere('p.exampleField = :val')
          ->setParameter('val', $value)
          ->getQuery()
          ->getOneOrNullResult()
      ;
  }
  */
}
