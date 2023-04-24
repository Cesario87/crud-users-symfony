<?php

namespace App\Repository;

use App\Entity\User;
use App\Model\User\UserRepositoryCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCriteria(UserRepositoryCriteria $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->orderBy('u.'.$criteria->sortBy, $criteria->sortOrder); // Order by the column specified in the sortBy parameter and the sorting direction specified in the sortOrder parameter

        if ($criteria->clientId != null) {
            $queryBuilder
                ->andWhere(':clientId MEMBER OF u.clientes')
                ->setParameter('clientId', $criteria->clientId);
        }

        $queryBuilder->setMaxResults($criteria->itemsPerPage);
        $queryBuilder->setFirstResult(($criteria->page - 1) * $criteria->itemsPerPage);

        return $queryBuilder->getQuery()->getResult();
    }
}
