<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use MZierdt\Albion\Entity\JournalEntity;

class JournalRepository extends Repository
{
    public function createOrUpdate(JournalEntity $journalEntity): void
    {
        $oldJournalEntity = $this->entityManager->getRepository(JournalEntity::class)->findOneBy(
            [
                'tier' => $journalEntity->getTier(),
                'name' => $journalEntity->getName(),
                'city' => $journalEntity->getCity(),
            ]
        );
        if ($oldJournalEntity !== null) {
            $oldJournalEntity->setSellOrderPrice($journalEntity->getSellOrderPrice());
            $oldJournalEntity->setBuyOrderPrice($journalEntity->getBuyOrderPrice());
            $oldJournalEntity->setSellOrderAge($journalEntity->getSellOrderAge());
            $oldJournalEntity->setBuyOrderAge($journalEntity->getBuyOrderAge());
            $this->update($oldJournalEntity);
        } else {
            $this->update($journalEntity);
        }
    }

    public function getJournalsFromCity(string $city): array
    {
        return $this->findBy(JournalEntity::class, ['city' => $city]) ?? [];
    }
}
