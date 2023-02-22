<?php

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntities\RefiningEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class RefiningEntityTest extends TestCase
{
    use ProphecyTrait;

    private RefiningEntity $refiningEntity;

    private ResourceEntity $resourceEntity;

    protected function setUp(): void
    {
        $this->resourceEntity = (new ResourceEntity())
            ->setTier(71)
            ->setName('metalBar')
            ->setCity('TestCity')
            ->setRealName('metalBar')
            ->setSellOrderPrice(13986)
            ->setBuyOrderPrice(12235);

        $this->refiningEntity = new RefiningEntity($this->resourceEntity);
    }

    public function testGetResourceEntity(): void
    {
        $this->assertEquals($this->resourceEntity, $this->refiningEntity->getRefinedResource());
    }

    public function testGetTierColor(): void
    {
        $this->assertEquals('7', $this->refiningEntity->getTierColor());
    }

    public function testGetRawResource(): void
    {
        $this->refiningEntity->setRawResource($this->resourceEntity);
        $this->assertEquals($this->resourceEntity, $this->refiningEntity->getRawResource());
    }

    public function testGetLowerResource(): void
    {
        $this->refiningEntity->setLowerResource($this->resourceEntity);
        $this->assertEquals($this->resourceEntity, $this->refiningEntity->getLowerResource());
    }

    public function testGetAmount(): void
    {
        $this->refiningEntity->setAmount(15);
        $this->assertEquals(15, $this->refiningEntity->getAmount());
    }
}
