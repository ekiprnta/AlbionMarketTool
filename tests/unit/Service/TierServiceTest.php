<?php

namespace unit\Service;

use MZierdt\Albion\Service\TierService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TierServiceTest extends TestCase
{
    use  ProphecyTrait;

    private TierService $tierService;

    protected function setUp(): void
    {
        $this->tierService = new TierService();
    }

    /**
     * @dataProvider itemIds
     */
    public function testSplitIntoTierAndName(string $testData, array $expectedResult): void
    {
        $this->assertEquals($expectedResult, $this->tierService->splitIntoTierAndName($testData));
    }

    public function itemIds(): array
    {
        return [
            [
                'T2_metalBar',
                [
                    'tier' => 20,
                    'name' => 'metalbar',
                ],
            ],
            [
                'T8_Journal_Hunter_FULL',
                [
                    'tier' => 80,
                    'name' => 'journal_hunter_full',
                ],
            ],
            [
                'T8_SHOES_PLATE_SET3@3',
                [
                    'tier' => 83,
                    'name' => 'shoes_plate_set3',
                ],
            ],
            [
                'Bla_BLu',
                [
                    'tier' => 0,
                    'name' => 'blu',
                ],
            ],
            [
                'QUESTITEM_TOKEN_ROYAL_T5',
                [
                    'tier' => 50,
                    'name' => 'questitem_token_royal',
                ],
            ],
        ];
    }

    /**
     * @dataProvider journalSplitData
     */
    public function testJournalSplitter(string $testData, array $expectedResult): void
    {
        $this->assertEquals($expectedResult, $this->tierService->journalSplitter($testData));
    }

    public function journalSplitData(): array
    {
        return [
            [
                'journal_hunter_empty',
                [
                    'class' => 'hunter',
                    'fillStatus' => 'empty',
                ],
            ],
            [
                'journal_warrior_full',
                [
                    'class' => 'warrior',
                    'fillStatus' => 'full',
                ],
            ],
            [
                'abc_DEF_ghi',
                [
                    'class' => 'def',
                    'fillStatus' => 'ghi',
                ],
            ],
        ];
    }
}
