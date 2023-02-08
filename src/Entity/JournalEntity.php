<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use Doctrine\ORM\Mapping\ChangeTrackingPolicy;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Table(name: 'journals')]
class JournalEntity extends AlbionItemEntity
{
    final public const JOURNAL_WARRIOR = 'JOURNAL_WARRIOR';
    final public const JOURNAL_MAGE = 'JOURNAL_MAGE';
    final public const JOURNAL_HUNTER = 'JOURNAL_HUNTER';

    #[Column(type: 'integer', nullable: true)]
    private ?int $fameToFill = null;
    #[Id, Column(type: 'string', nullable: true)]
    private ?string $fillStatus = null; //full empty

    public function setFameToFill(int $fameToFill): self
    {
        $this->fameToFill = $fameToFill;
        return $this;
    }

    public function setFillStatus(string $fillStatus): self
    {
        $this->fillStatus = $fillStatus;
        return $this;
    }

    public function getFameToFill(): int
    {
        return $this->fameToFill;
    }

    public function getFillStatus(): string
    {
        return $this->fillStatus;
    }
}
