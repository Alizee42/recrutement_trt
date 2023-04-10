<?php

namespace App\Repository;

use App\Entity\Recruteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recruteur>
 *
 * @method Recruteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recruteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recruteur[]    findAll()
 * @method Recruteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecruteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recruteur::class);
    }

    public function save($email, $password,$compagnyName,$compagnyAdress,$isValid): void
    {
        $recruteur = new Recruteur();
        $recruteur->setEmail($email);
        $recruteur->setPassword($password);
        $recruteur->setCompagnyName($compagnyName);
        $recruteur->setCompagnyAdress($compagnyAdress);
        $recruteur->setIsValid($isValid);
        $this->getEntityManager()->persist($recruteur);
        $this->getEntityManager()->flush();
    }

    public function update(Recruteur $recruteur): Recruteur
    {
        $this->getEntityManager()->persist($recruteur);
        $this->getEntityManager()->flush();

        return $recruteur;
    }

    public function remove(Recruteur $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return Recruteur[] Returns an array of Recruteur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recruteur
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
