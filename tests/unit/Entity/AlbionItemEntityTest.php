<?php

declare(strict_types=1);

namespace unit\Entity;

use MZierdt\Albion\Entity\AlbionItemEntity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class AlbionItemEntityTest extends TestCase
{
    use ProphecyTrait;

    private AlbionItemEntity $entity;

    public function testSetSellOrderAge(): void
    {
        $this->entity->setSellOrderAge(10);
        $this->assertEquals(10, $this->entity->getSellOrderAge());
    }

    public function testSetBuyOrderAge(): void
    {
        $this->entity->setBuyOrderAge(10);
        $this->assertEquals(10, $this->entity->getBuyOrderAge());
    }

    public function testCalculateBuyOrderAge(): void
    {
        $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-02-08 20:00:00');

        /** @var AlbionItemEntity|MockObject $albionItemEntity */
        $albionItemEntity = $this->getMockBuilder(AlbionItemEntity::class)
            ->onlyMethods(['getCurrentTime'])
            ->getMock();

        $albionItemEntity->method('getCurrentTime')
            ->willReturn($now);

        $albionItemEntity->calculateBuyOrderAge('2023-02-08T10:00:00');
        $this->assertEquals(540, $albionItemEntity->getBuyOrderAge());
    }

    public function testCalculateSellOrderAge(): void
    {
        $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-02-08 20:00:00');

        /** @var AlbionItemEntity|MockObject $albionItemEntity */
        $albionItemEntity = $this->getMockBuilder(AlbionItemEntity::class)
            ->onlyMethods(['getCurrentTime'])
            ->getMock();

        $albionItemEntity->method('getCurrentTime')
            ->willReturn($now);

        $albionItemEntity->calculateSellOrderAge(null);
        $this->assertEquals(24947, $albionItemEntity->getSellOrderAge());
    }

    protected function setUp(): void
    {
        $this->entity = new AlbionItemEntity();
    }
}
