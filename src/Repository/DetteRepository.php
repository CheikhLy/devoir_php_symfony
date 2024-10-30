<?php

namespace App\Repository;
use App\enum\StatusDette;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Dette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dette>
 *
 * @method Dette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dette[]    findAll()
 * @method Dette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dette::class);
    }

    public function add(Dette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Dette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findDetteByClient(int $idClient, string $status = "IMPAYE",  int $limit = 2, int $page = 1): Paginator
    {
        $query = $this->createQueryBuilder('d');
        $query->where('d.client = :idClient');
        $query->setParameter('idClient', $idClient);
        if ($status == StatusDette::IMPAYE->value) {
            $query->andWhere('d.montant != d.montantVerser');
        }
        if ($status == StatusDette::PAYE->value) {
            $query->andWhere('d.montant = d.montantVerser');
        }

        $query->orderBy('d.createAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();
        return new Paginator($query);
    }

//    /**
//     * @return Dette[] Returns an array of Dette objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Dette
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
