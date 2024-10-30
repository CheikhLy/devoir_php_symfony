<?php

namespace App\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function add(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function paginateClients(int $page, int $limit): Paginator
    {

        $query = $this->createQueryBuilder('c')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->orderBy('c.id', 'ASC')
            ->getQuery();
        return new Paginator($query);
    }
    public function countAllClients(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function remove(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Client[] Returns an array of Client objects
    */
   public function findClinetBy(ClientSearchDto $clientSearchDto,int $page, int $limit): Paginator
   {
       $query= $this->createQueryBuilder('c');
       if(!empty($clientSearchDto->telephon)){
           $query->andWhere('c.telephon = :telephon')
               ->setParameter('telephon', $clientSearchDto->telephon);
       }

       if(!empty($clientSearchDto->surname)){
           $query->andWhere('c.surname = :surname')
               ->setParameter('surname', $clientSearchDto->surname);
       }

        $query->orderBy('c.id', 'ASC')
           ->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit)
           ->getQuery();
       return new Paginator($query);
    }  

    public function findDetteByClient(int $idClient, StatusDette $status= StatusDette::PAYE) {
        $query = $this->createQueryBuilder('c');
        $query->join('c.dettes', 'd');
        $query->where('c.id = :idClient');
        $query->setParameter('idClient', $idClient);
        if ($status->value == StatusDette::IMPAYE->value) {
            // dd($status->value == StatusDette::Impaye->value);
            $query->andWhere('d.montant != d.montantVerser');
        }
        if ($status->value == StatusDette::PAYE->value) {
            $query->andWhere('d.montant = d.montantVerser');
        }

        return $query->orderBy('d.createAt', 'DESC')
            ->getQuery()
            ->getSingleResult();


    }


//    public function findOneBySomeField($value): ?Client
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
