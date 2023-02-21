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
            if ($journalEntity->getSellOrderPrice() !== 0) {
                $oldJournalEntity->setSellOrderPrice($journalEntity->getSellOrderPrice());
                $oldJournalEntity->setSellOrderDate($journalEntity->getSellOrderDate());
            }
            if ($journalEntity->getBuyOrderPrice() !== 0) {
                $oldJournalEntity->setBuyOrderPrice($journalEntity->getBuyOrderPrice());
                $oldJournalEntity->setBuyOrderDate($journalEntity->getBuyOrderDate());
            }
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
