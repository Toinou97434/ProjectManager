<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function add(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getProjects(): mixed
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'pu')
            ->leftJoin('p.users', 'pu')
            ->getQuery()->getResult()
        ;
    }

    public function getUserProjects(?User $user)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'pu', 'pc', 'pt')
            ->leftJoin('p.users', 'pu')
            ->join('p.client', 'pc')
            ->leftJoin('p.timesheets', 'pt')
            ->where('pu.id IN (:user)')
            ->setParameter('user', $user)
            ->getQuery()->getResult()
        ;
    }

    public function getUserProjectsWithTimesheet(?User $user, $date = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p', 'pu', 'pc', 'pt')
            ->leftJoin('p.users', 'pu')
            ->join('p.client', 'pc')
            ->leftJoin('p.timesheets', 'pt')
            ->where('pu.id IN (:user)')
            ->andWhere('pt.created_by LIKE (:user)')
            ->setParameter('user', $user)
            ->andWhere('pt.created_at = :now')
            ->setParameter('now', new \DateTime())
        ;

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Project[] Returns an array of Project objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Project
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
