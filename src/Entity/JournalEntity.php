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

    #[Column(type: 'integer')]
    private int $fameToFill;
    #[Id, Column(type: 'string')]
    private string $fillStatus; //full empty

    public function __construct(array $journalData)
    {
        parent::__construct($journalData);

        $this->fameToFill = (int) $journalData['fameToFill'];
        $this->fillStatus = $journalData['fillStatus'];
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
