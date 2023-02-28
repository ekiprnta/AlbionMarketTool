<?php

declare(strict_types=1);

namespace unit\Entity;

use DateTimeImmutable;
use MZierdt\Albion\Entity\AlbionItemEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class AlbionItemEntityTest extends TestCase
{
    use ProphecyTrait;

    private AlbionItemEntity $entity;

    public function testSetSellOrderDate(): void
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-03-22 12:12:12');
        $this->entity->setSellOrderDate($date);
        $this->assertEquals($date, $this->entity->getSellOrderDate());
    }

    public function testSetBuyOrderDate(): void
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

    /**
     * @dataProvider provideDates
     */
    public function testCalculateBuyOrderAge(DateTimeImmutable $result, ?string $date): void
    {
        $this->entity->calculateBuyOrderDate($date);
        $this->assertEquals($result, $this->entity->getBuyOrderDate());
    }

    /**
     * @dataProvider provideDates
     */
    public function testCalculateSellOrderAge(DateTimeImmutable $result, ?string $date): void
    {
        $this->entity->calculateSellOrderDate($date);
        $this->assertEquals($result, $this->entity->getSellOrderDate());
    }

    public function provideDates(): array
    {
        return [
            [new DateTimeImmutable('1970-03-22 12:12:12'), null],
            [new DateTimeImmutable('2023-02-23 14:20:50'), '2023-02-23 13:20:50'],
        ];
    }

    protected function setUp(): void
    {
        $this->entity = new AlbionItemEntity();
    }
}
