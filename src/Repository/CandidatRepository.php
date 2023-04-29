<?php

namespace App\Repository;

use App\Entity\Candidat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidat>
 *
 * @method Candidat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidat[]    findAll()
 * @method Candidat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidat::class);
    }

    public function save($firstName, $lastName, $email, $password,$cv,$isValid): void
    {
        $candidat = new Candidat();
        $candidat->setFirstName($firstName);
        $candidat->setLastName($lastName);
        $candidat->setEmail($email);
        $candidat->setPassword($password);
        $candidat->setCv($cv);
        $candidat->setIsValid($isValid);
        $candidat->setRole("candidat");
        $this->getEntityManager()->persist($candidat);
        $this->getEntityManager()->flush();
    }

    public function update(Candidat $candidat): Candidat
    {
        $this->getEntityManager()->persist($candidat);
        $this->getEntityManager()->flush();

        return $candidat;
    }

    public function remove(Candidat $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return Candidat[] Returns an array of Candidat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Candidat
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
