<?php

namespace App\Repository;

use App\Entity\Main;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Main|null find($idUser, $lockMode = null, $lockVersion = null)
 * @method Main|null findOneBy(array $criteria, array $orderBy = null)
 * @method Main[]    findAll()
 * @method Main[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Main::class);
    }
}