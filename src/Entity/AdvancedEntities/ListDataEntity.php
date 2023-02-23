<?php

namespace MZierdt\Albion\Entity\AdvancedEntities;

use Doctrine\ORM\Mapping\ChangeTrackingPolicy;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\ResourceEntity;

#[Entity]
#[ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Table(name: 'listData')]
class ListDataEntity
{
    #[Column(type: 'integer')]
    #[Id, GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'fortsterlingObject', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $fortsterlingObject;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'martlockObject', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $martlockObject;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'bridgewatchObject', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $bridgewatchObject;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'lymhurstObject', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $lymhurstObject;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'thetfordObject', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $thetfordObject;

    #[Column(type: 'string', nullable: true)]
    private ?string $cheapestObjectCitySellOrder;
    #[Column(type: 'string', nullable: true)]
    private ?string $mostExpensiveObjectCitySellOrder;

    #[Column(type: 'string', nullable: true)]
    private ?string $cheapestObjectCityBuyOrder;
    #[Column(type: 'string', nullable: true)]
    private ?string $mostExpensiveObjectCityBuyOrder;

    #[Column(type: 'string', nullable: true)]
    private ?string $type;

    #[Column(type: 'integer', nullable: true)]
    private readonly ?int $tierColor;

    public function __construct(ResourceEntity $fortsterlingObject)
    {
        $this->fortsterlingObject = $fortsterlingObject;
        $this->tierColor = (int) ($fortsterlingObject->getTier() / 10);
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getTierColor(): ?int
    {
        return $this->tierColor;
    }

    public function getCheapestObjectCityBuyOrder(): ?string
    {
        return $this->cheapestObjectCityBuyOrder;
    }

    public function setCheapestObjectCityBuyOrder(?string $cheapestObjectCityBuyOrder): void
    {
        $this->cheapestObjectCityBuyOrder = $cheapestObjectCityBuyOrder;
    }

    public function getMostExpensiveObjectCityBuyOrder(): ?string
    {
        return $this->mostExpensiveObjectCityBuyOrder;
    }

    public function setMostExpensiveObjectCityBuyOrder(?string $mostExpensiveObjectCityBuyOrder): void
    {
        $this->mostExpensiveObjectCityBuyOrder = $mostExpensiveObjectCityBuyOrder;
    }

    public function getCheapestObjectCitySellOrder(): ?string
    {
        return $this->cheapestObjectCitySellOrder;
    }

    public function setCheapestObjectCitySellOrder(?string $cheapestObjectCitySellOrder): void
    {
        $this->cheapestObjectCitySellOrder = $cheapestObjectCitySellOrder;
    }

    public function getMostExpensiveObjectCitySellOrder(): ?string
    {
        return $this->mostExpensiveObjectCitySellOrder;
    }

    public function setMostExpensiveObjectCitySellOrder(?string $mostExpensiveObjectCitySellOrder): void
    {
        $this->mostExpensiveObjectCitySellOrder = $mostExpensiveObjectCitySellOrder;
    }

    public function getMartlockObject(): ResourceEntity|ItemEntity
    {
        return $this->martlockObject;
    }

    public function setMartlockObject(ResourceEntity|ItemEntity $martlockObject): void
    {
        $this->martlockObject = $martlockObject;
    }

    public function getBridgewatchObject(): ResourceEntity|ItemEntity
    {
        return $this->bridgewatchObject;
    }

    public function setBridgewatchObject(ResourceEntity|ItemEntity $bridgewatchObject): void
    {
        $this->bridgewatchObject = $bridgewatchObject;
    }

    public function getLymhurstObject(): ResourceEntity|ItemEntity
    {
        return $this->lymhurstObject;
    }

    public function setLymhurstObject(ResourceEntity|ItemEntity $lymhurstObject): void
    {
        $this->lymhurstObject = $lymhurstObject;
    }

    public function getThetfordObject(): ResourceEntity|ItemEntity
    {
        return $this->thetfordObject;
    }

    public function setThetfordObject(ResourceEntity|ItemEntity $thetfordObject): void
    {
        $this->thetfordObject = $thetfordObject;
    }

    public function getFortsterlingObject(): ResourceEntity|ItemEntity
    {
        return $this->fortsterlingObject;
    }
}
