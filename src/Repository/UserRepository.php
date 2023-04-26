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
    
    if ($criteria->id != null) {
        $queryBuilder
            ->andWhere('u.id = :id')
            ->setParameter('id', $criteria->id);
    }

    if ($criteria->nombre != null) {
        $queryBuilder
            ->andWhere('u.nombre = :nombre')
            ->setParameter('nombre', $criteria->nombre);
    }

    if ($criteria->apellidos != null) {
        $queryBuilder
            ->andWhere('u.apellidos = :apellidos')
            ->setParameter('apellidos', $criteria->apellidos);
    }

    if ($criteria->poblacion != null) {
        $queryBuilder
            ->andWhere('u.poblacion = :poblacion')
            ->setParameter('poblacion', $criteria->poblacion);
    }

    if ($criteria->categoria != null) {
        $queryBuilder
            ->andWhere('u.categoria = :categoria')
            ->setParameter('categoria', $criteria->categoria);
    }

    if ($criteria->edad != null) {
        $queryBuilder
            ->andWhere('u.edad = :edad')
            ->setParameter('edad', $criteria->edad);
    }

    if ($criteria->activo != null) {
        $queryBuilder
            ->andWhere('u.activo = :activo')
            ->setParameter('activo', $criteria->activo);
    }

    if ($criteria->createdAt != null) {
        $queryBuilder
            ->andWhere('u.createdAt = :createdAt')
            ->setParameter('createdAt', $criteria->createdAt);
    }

    $queryBuilder->setMaxResults($criteria->itemsPerPage);
    $queryBuilder->setFirstResult(($criteria->page - 1) * $criteria->itemsPerPage);

    return $queryBuilder->getQuery()->getResult();
}

}
