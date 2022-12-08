<?php

namespace Service;

use MZierdt\Albion\Service\TierService;
use MZierdt\Albion\Service\UploadHelper;
use PHPUnit\Framework\TestCase;

class UploadHelperTest extends TestCase
{
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

    /** @dataProvider dataForAdjustResource */
    public function testAdjustResourceArray(array $expectedArray, array $testData): void
    {
        $stats = ['bonusCity' => '', 'realName' => ''];
        $this->assertEquals($expectedArray, UploadHelper::adjustResourceArray($testData, $stats));
    }

    public function dataForAdjustResource(): array
    {
        return [
            [
                [
                    [
                        'tier' => 0,
                        'name' => '',
                        'city' => '',
                        'realName' => '',
                        'sellOrderPrice' => '',
                        'sellOrderPriceDate' => '',
                        'buyOrderPrice' => '',
                        'buyOrderPriceDate' => '',
                        'bonusCity' => '',
                    ]
                ],
                [$this->testDataEmpty]
            ],
            [
                [
                    [
                        'tier' => 2,
                        'name' => 'testitema',
                        'city' => 'city',
                        'realName' => '',
                        'sellOrderPrice' => '10',
                        'sellOrderPriceDate' => '2010-00-00T00:00:00',
                        'buyOrderPrice' => '10',
                        'buyOrderPriceDate' => '2000-00-00T00:00:00',
                        'bonusCity' => '',
                    ]
                ],
                [$this->testDataA]
            ],
            [
                [
                    ['tier' => 8,
                    'name' => 'testitemb',
                    'city' => 'city',
                    'realName' => '',
                    'sellOrderPrice' => '1100',
                    'sellOrderPriceDate' => '2000-00-00T12:00:00',
                    'buyOrderPrice' => '1000',
                    'buyOrderPriceDate' => '2000-00-00T12:00:00',
                    'bonusCity' => '',
                ]
            ],
            [$this->testDataB]
        ],
        ];
    }

    /** @dataProvider dataForAdjustJournals */
    public function testAdjustJournals(array $expectedArray, array $testData): void
    {
        $stats = [
            '2' => ['fameToFill' => '900', 'weight' => '10'],
            '8' => ['fameToFill' => '10000', 'weight' => '100'],
            '' => ['fameToFill' => '10000', 'weight' => '100'],
        ];

        TierService::splitIntoTierAndName($testData['item_id'])->willReturn()

        $this->assertEquals($expectedArray, UploadHelper::adjustJournals($testData, $stats));
    }

    public function dataForAdjustJournals(): array
    {
        return
            [
                [
                    [],
                    [$this->testDataEmpty]
                ],
//            [
//                [
//                    [
//                        'tier' => 2,
//                        'name' => 'testitema',
//                        'city' => 'city',
//                        'realName' => '',
//                        'sellOrderPrice' => '10',
//                        'sellOrderPriceDate' => '2010-00-00T00:00:00',
//                        'buyOrderPrice' => '10',
//                        'buyOrderPriceDate' => '2000-00-00T00:00:00',
//                        'bonusCity' => '',
//                    ]
//                ],
//                [$this->testDataA]
//            ],
//            [
//                [
//                    [
//                        'tier' => 8,
//                        'name' => 'testitemb',
//                        'city' => 'city',
//                        'realName' => '',
//                        'sellOrderPrice' => '1100',
//                        'sellOrderPriceDate' => '2000-00-00T12:00:00',
//                        'buyOrderPrice' => '1000',
//                        'buyOrderPriceDate' => '2000-00-00T12:00:00',
//                        'bonusCity' => '',
//                    ]
//                ],
//                [$this->testDataB]
//            ],
            ];
    }
}