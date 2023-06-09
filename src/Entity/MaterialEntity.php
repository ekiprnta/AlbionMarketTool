<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use Doctrine\ORM\Mapping\ChangeTrackingPolicy;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Table(name: 'materials')]
class MaterialEntity extends AlbionItemEntity
{
    #[Column(type: 'string', nullable: true)]
    protected ?string $type = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }
}
