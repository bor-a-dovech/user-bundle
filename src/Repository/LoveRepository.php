<?php

namespace Glavnivc\UserBundle\Repository;

use Glavnivc\UserBundle\Entity\Love;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Love|null find($id, $lockMode = null, $lockVersion = null)
 * @method Love|null findOneBy(array $criteria, array $orderBy = null)
 * @method Love[]    findAll()
 * @method Love[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Love::class);
    }
}
