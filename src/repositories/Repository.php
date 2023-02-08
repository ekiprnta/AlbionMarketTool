<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use Doctrine\ORM\EntityManager;

class Repository
{
    public function __construct(
        protected readonly EntityManager $entityManager
    ) {
    }

    public function update(mixed $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
    }

    public function findBy(string $class, array $params, array $sort = []): ?array
    {
        return $this->entityManager->getRepository($class)
            ->findBy($params, $sort);
    }

    public function delete(mixed $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush($entity);
    }
}
