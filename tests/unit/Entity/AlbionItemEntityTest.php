<?php

declare(strict_types=1);

namespace unit\Entity;

use DateTimeImmutable;
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
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-03-22 12:12:12');
        $this->entity->setSellOrderDate($date);
        $this->assertEquals($date, $this->entity->getSellOrderDate());
    }

    public function testSetBuyOrderAge(): void
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-03-22 12:12:12');
        $this->entity->setBuyOrderDate($date);
        $this->assertEquals($date, $this->entity->getBuyOrderDate());
    }

    public function testGetId(): void
    {
        $this->entity->setId(1);
        $this->assertEquals(1, $this->entity->getId());
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

        $albionItemEntity->calculateBuyOrderDate('2023-02-08T10:00:00');
        $this->assertEquals(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-02-08 11:00:00'),
            $albionItemEntity->getBuyOrderDate()
        );
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

        $albionItemEntity->calculateSellOrderDate(null);
        $this->assertEquals(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-03-22 12:12:12'),
            $albionItemEntity->getSellOrderDate()
        );
    }

    protected function setUp(): void
    {
        $this->entity = new AlbionItemEntity();
    }
}
