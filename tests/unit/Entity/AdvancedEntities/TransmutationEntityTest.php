<?php

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntities\TransmutationEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TransmutationEntityTest extends TestCase
{
    use ProphecyTrait;

    private TransmutationEntity $transEntity;

    protected function setUp(): void
    {
        $this->transEntity = new TransmutationEntity('40to62', ['50', '60', '61', '62'], 'cloth');
    }

    public function testGetResourceType(): void
    {
        $this->assertEquals('cloth', $this->transEntity->getResourceType());
    }

    public function testGetEndTierColor(): void
    {
        $this->transEntity->setEndTierColor('4');
        $this->assertEquals('4', $this->transEntity->getEndTierColor());
    }

    public function testGetStartResource(): void
    {
        $this->transEntity->setStartResource((new ResourceEntity())->setTier(10)->setCity('TestCity'));
        $this->assertEquals(
            (new ResourceEntity())->setTier(10)
                ->setCity('TestCity'),
            $this->transEntity->getStartResource()
        );
    }

    public function testGetEndResource(): void
    {
        $this->transEntity->setEndResource((new ResourceEntity())->setTier(10)->setCity('TestCity'));
        $this->assertEquals(
            (new ResourceEntity())->setTier(10)
                ->setCity('TestCity'),
            $this->transEntity->getEndResource()
        );
    }

    public function testGetPathName(): void
    {
        $this->assertEquals('40to62', $this->transEntity->getPathName());
    }

    public function testGetTransmutationPath(): void
    {
        $this->assertEquals(['50', '60', '61', '62'], $this->transEntity->getTransmutationPath());
    }

    public function testGetTransmutationPrice(): void
    {
        $this->transEntity->setTransmutationPrice(124.5);
        $this->assertEquals(124.5, $this->transEntity->getTransmutationPrice());
    }
}
