<?php

namespace unit\Service;

use MZierdt\Albion\Service\TierService;
use MZierdt\Albion\Service\UploadHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class UploadHelperTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|TierService $tierService;
    private UploadHelper $uploadHelper;
    private array $testDataEmpty = [
        'item_id' => '',
        'city' => '',
        'sell_price_min' => '',
        'sell_price_min_date' => '',
        'buy_price_max' => '',
        'buy_price_max_date' => '',
        'quality' => '',
    ];
    private array $testDataA = [
        'item_id' => 'T2_TestItemA',
        'city' => 'city',
        'sell_price_min' => '10',
        'sell_price_min_date' => '2010-00-00T00:00:00',
        'buy_price_max' => '10',
        'buy_price_max_date' => '2000-00-00T00:00:00',
        'quality' => 'good',
    ];
    private array $testDataB = [
        'item_id' => 'T8_TestItemB',
        'city' => 'city',
        'sell_price_min' => '1100',
        'sell_price_min_date' => '2000-00-00T12:00:00',
        'buy_price_max' => '1000',
        'buy_price_max_date' => '2000-00-00T12:00:00',
        'quality' => 'good',
    ];

    protected function setUp(): void
    {
        $this->tierService = $this->prophesize(TierService::class);
        $this->uploadHelper = new UploadHelper($this->tierService->reveal());
    }

    /**
     * @dataProvider dataForAdjustResource
     */
    public function testAdjustResourceArray(array $expectedArray, array $testData, string $testName): void
    {
        $this->tierService->splitIntoTierAndName($testData[0]['item_id'])->willReturn(
            [
                'name' => $testName,
                'tier' => 20,
            ]
        );

        $stats = [
            'bonusCity' => '',
            'realName' => '',
        ];
        $this->assertEquals($expectedArray, $this->uploadHelper->adjustResourceArray($testData, $stats));
    }

    public function dataForAdjustResource(): array
    {
        return [
            [
                [
                    [
                        'tier' => 20,
                        'name' => 'alfred',
                        'city' => '',
                        'realName' => '',
                        'sellOrderPrice' => '',
                        'sellOrderPriceDate' => '',
                        'buyOrderPrice' => '',
                        'buyOrderPriceDate' => '',
                        'bonusCity' => '',
                    ],
                ],
                [$this->testDataEmpty],
                'alfred',
            ],
            [
                [
                    [
                        'tier' => 20,
                        'name' => 'alfred',
                        'city' => 'city',
                        'realName' => '',
                        'sellOrderPrice' => '10',
                        'sellOrderPriceDate' => '2010-00-00T00:00:00',
                        'buyOrderPrice' => '10',
                        'buyOrderPriceDate' => '2000-00-00T00:00:00',
                        'bonusCity' => '',
                    ],
                ],
                [$this->testDataA],
                'alfred_level1',
            ],
            [
                [
                    [
                        'tier' => 20,
                        'name' => 'alfred',
                        'city' => 'city',
                        'realName' => '',
                        'sellOrderPrice' => '1100',
                        'sellOrderPriceDate' => '2000-00-00T12:00:00',
                        'buyOrderPrice' => '1000',
                        'buyOrderPriceDate' => '2000-00-00T12:00:00',
                        'bonusCity' => '',
                    ],
                ],
                [$this->testDataB],
                'alfred',
            ],
        ];
    }

    /**
     * @dataProvider dataForAdjustJournals
     */
    public function testAdjustJournals(array $expectedArray, array $testData): void
    {
        $stats = [
            '2' => ['fameToFill' => '900', 'weight' => '10'],
        ];

        $this->tierService->splitIntoTierAndName($testData[0]['item_id'])->willReturn(
            ['name' => 'alfred', 'tier' => 2]
        );
        $this->tierService->journalSplitter('alfred')
            ->willReturn(['class' => 'alfred', 'fillStatus' => 'full']);

        $this->assertEquals($expectedArray, $this->uploadHelper->adjustJournals($testData, $stats));
    }

    public function dataForAdjustJournals(): array
    {
        return [
            [
                [
                    [
                        'tier' => 2,
                        'name' => 'alfred',
                        'city' => '',
                        'fameToFill' => '900',
                        'sellOrderPrice' => '',
                        'sellOrderPriceDate' => '',
                        'buyOrderPrice' => '',
                        'buyOrderPriceDate' => '',
                        'weight' => '10',
                        'fillStatus' => 'full',
                        'class' => 'alfred',
                    ],
                ],
                [$this->testDataEmpty],
            ],
            [
                [
                    [
                        'tier' => 2,
                        'name' => 'alfred',
                        'city' => 'city',
                        'fameToFill' => '900',
                        'sellOrderPrice' => '10',
                        'sellOrderPriceDate' => '2010-00-00T00:00:00',
                        'buyOrderPrice' => '10',
                        'buyOrderPriceDate' => '2000-00-00T00:00:00',
                        'weight' => '10',
                        'fillStatus' => 'full',
                        'class' => 'alfred',
                    ],
                ],
                [$this->testDataA],
            ],
            [
                [
                    [
                        'tier' => 2,
                        'name' => 'alfred',
                        'city' => 'city',
                        'fameToFill' => '900',
                        'sellOrderPrice' => '1100',
                        'sellOrderPriceDate' => '2000-00-00T12:00:00',
                        'buyOrderPrice' => '1000',
                        'buyOrderPriceDate' => '2000-00-00T12:00:00',
                        'weight' => '10',
                        'fillStatus' => 'full',
                        'class' => 'alfred',
                    ],
                ],
                [$this->testDataB],
            ],
        ];
    }

    /**
     * @dataProvider dataForAdjustItems
     */
    public function testAdjustItems(array $expectedArray, array $testData): void
    {
        $stats = [
            'id_snippet' => 'james',
            'primaryResource' => 'air',
            'primaryResourceAmount' => 10,
            'secondaryResource' => 'water',
            'secondaryResourceAmount' => 10,
            'bonusCity' => 'cityB',
            'realName' => 'fire',
            'class' => 'smith',
            'weaponGroup' => 'Hammer',
        ];

        $this->tierService->splitIntoTierAndName($testData[0]['item_id'])->willReturn(
            ['name' => 'alfred', 'tier' => 2]
        );

        $this->assertEquals($expectedArray, $this->uploadHelper->adjustItems($testData, $stats));
    }

    public function dataForAdjustItems(): array
    {
        return [
            [
                [
                    [
                        'tier' => 2,
                        'name' => 'alfred',
                        'weaponGroup' => 'Hammer',
                        'realName' => 'fire',
                        'class' => 'smith',
                        'city' => '',
                        'quality' => '',
                        'sellOrderPrice' => '',
                        'sellOrderPriceDate' => '',
                        'buyOrderPrice' => '',
                        'buyOrderPriceDate' => '',
                        'primaryResource' => 'air',
                        'primaryResourceAmount' => 10,
                        'secondaryResource' => 'water',
                        'secondaryResourceAmount' => 10,
                        'bonusCity' => 'cityB',
                        'fameFactor' => null,

                    ],
                ],
                [$this->testDataEmpty],
            ],
            [
                [
                    [
                        'tier' => 2,
                        'name' => 'alfred',
                        'weaponGroup' => 'Hammer',
                        'realName' => 'fire',
                        'class' => 'smith',
                        'city' => 'city',
                        'quality' => 'good',
                        'sellOrderPrice' => '10',
                        'sellOrderPriceDate' => '2010-00-00T00:00:00',
                        'buyOrderPrice' => '10',
                        'buyOrderPriceDate' => '2000-00-00T00:00:00',
                        'primaryResource' => 'air',
                        'primaryResourceAmount' => 10,
                        'secondaryResource' => 'water',
                        'secondaryResourceAmount' => 10,
                        'bonusCity' => 'cityB',
                        'fameFactor' => null,

                    ],
                ],
                [$this->testDataA],
            ],
            [
                [
                    [
                        'tier' => 2,
                        'name' => 'alfred',
                        'weaponGroup' => 'Hammer',
                        'realName' => 'fire',
                        'class' => 'smith',
                        'city' => 'city',
                        'quality' => 'good',
                        'sellOrderPrice' => '1100',
                        'sellOrderPriceDate' => '2000-00-00T12:00:00',
                        'buyOrderPrice' => '1000',
                        'buyOrderPriceDate' => '2000-00-00T12:00:00',
                        'primaryResource' => 'air',
                        'primaryResourceAmount' => 10,
                        'secondaryResource' => 'water',
                        'secondaryResourceAmount' => 10,
                        'bonusCity' => 'cityB',
                        'fameFactor' => null,

                    ],
                ],
                [$this->testDataB],
            ],
        ];
    }
}
