<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use Doctrine\ORM\EntityManager;
use MZierdt\Albion\Entity\JournalEntity;

class JournalRepository
{
    public function __construct(private readonly EntityManager $entityManager
    ) {
    }

    public function update(JournalEntity $resourceEntity): void
    {
        $this->entityManager->persist($resourceEntity);
        $this->entityManager->flush($resourceEntity);
    }

    public function findBy(array $params, array $sort = []): ?array
    {
        return $this->entityManager->getRepository(JournalEntity::class)->findBy($params, $sort);
    }

    public function delete(JournalEntity $resourceEntity): void
    {
        $this->entityManager->remove($resourceEntity);
        $this->entityManager->flush($resourceEntity);
    }

    public function createOrUpdate(JournalEntity $journalEntity): void
    {
        $oldJournalEntity = $this->entityManager->getRepository(JournalEntity::class)->findOneBy(
            [
                'tier' => $journalEntity->getTier(),
                'name' => $journalEntity->getName(),
                'city' => $journalEntity->getCity()
            ]
        );
        if ($oldJournalEntity !== null) {
            $oldJournalEntity->setSellOrderPrice($journalEntity->getSellOrderPrice());
            $oldJournalEntity->setBuyOrderAge($journalEntity->getBuyOrderPrice());
            $oldJournalEntity->setSellOrderAge($journalEntity->getSellOrderAge());
            $oldJournalEntity->setBuyOrderAge($journalEntity->getBuyOrderAge());
            $this->update($oldJournalEntity);
        } else {
            $this->update($journalEntity);
        }
    }

    public function getJournalsFromCity(string $city): array
    {
        return $this->findBy(['city' => $city]) ?? [];
    }
}
