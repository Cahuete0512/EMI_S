<?php

namespace App\Repository;

use App\Entity\Soiree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Soiree|null find($idUser, $lockMode = null, $lockVersion = null)
 * @method Soiree|null findOneBy(array $criteria, array $orderBy = null)
 * @method Soiree[]    findAll()
 * @method Soiree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoireeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Soiree::class);
    }
}