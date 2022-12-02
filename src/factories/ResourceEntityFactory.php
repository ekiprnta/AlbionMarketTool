<?php

namespace MZierdt\Albion\factories;

use MZierdt\Albion\Entity\ResourceEntity;

class ResourceEntityFactory
{


    public static function getEmtpyResourceEntity()
    {
        return new ResourceEntity([
            'tier' => '0',
            'name' => '',
            'city' => '',
            'realName' => '',
            'sellOrderPrice' => 0,
            'sellOrderPriceDate' => null,
            'buyOrderPriceDate' => null,
            'buyOrderPrice' => 0,
            'bonusCity' => '',
            'amountInStorage' => '',
            'weight' => 0,
        ]);
    }
}