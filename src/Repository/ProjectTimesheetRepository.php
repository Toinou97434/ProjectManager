<?php

namespace App\Repository;

use App\Entity\ProjectTimesheet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectTimesheet>
 *
 * @method ProjectTimesheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectTimesheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectTimesheet[]    findAll()
 * @method ProjectTimesheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectTimesheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectTimesheet::class);
    }

    public function add(ProjectTimesheet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProjectTimesheet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getUserTimesheets(User $user, array $options = [])
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t', 'tp', 'tu')
            ->join('t.project', 'tp')
            ->join('t.created_by', 'tu')
            ->where('t.created_by = :user')
            ->setParameter('user', $user)
        ;

        if ($options) {
            if (isset($options['project'])) {
                $project = $options['project'];
                $qb->andWhere('tp.id IN (:project)')
                    ->setParameter('project', $project)
                ;
            }

            if (isset($options['date'])) {
                $date = $options['date'];
                $start_date = $date->format('Y-m-d 00:00:00');
                $end_date = $date->format('Y-m-d 23:59:59');

                $qb->andWhere('t.created_at BETWEEN :start AND :end')
                    ->setParameter('start', $start_date)
                    ->setParameter('end', $end_date)
                ;
            }
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return ProjectTimesheet[] Returns an array of ProjectTimesheet objects
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

//    public function findOneBySomeField($value): ?ProjectTimesheet
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
