<?php

namespace MZierdt\Albion\Entity;

class ListDataEntity
{

    private ResourceEntity $martlockObject;
    private ResourceEntity $bridgewatchObject;
    private ResourceEntity $lymhurstObject;
    private ResourceEntity $thetfordObject;

    private string $cheapestObjectCitySellOrder;
    private string $mostExpensiveObjectCitySellOrder;

    private string $cheapestObjectCityBuyOrder;
    private string $mostExpensiveObjectCityBuyOrder;

    private string $tierColor;

    public function __construct(private readonly ResourceEntity $fortsterlingObject)
    {
        $this->tierColor = $this->fortsterlingObject->getTier()[0];
    }

    public function getTierColor(): string
    {
        return $this->tierColor;
    }

    public function getCheapestObjectCityBuyOrder(): string
    {
        return $this->cheapestObjectCityBuyOrder;
    }

    public function setCheapestObjectCityBuyOrder(string $cheapestObjectCityBuyOrder): void
    {
        $this->cheapestObjectCityBuyOrder = $cheapestObjectCityBuyOrder;
    }

    public function getMostExpensiveObjectCityBuyOrder(): string
    {
        return $this->mostExpensiveObjectCityBuyOrder;
    }

    public function setMostExpensiveObjectCityBuyOrder(string $mostExpensiveObjectCityBuyOrder): void
    {
        $this->mostExpensiveObjectCityBuyOrder = $mostExpensiveObjectCityBuyOrder;
    }

    public function getCheapestObjectCitySellOrder(): string
    {
        return $this->cheapestObjectCitySellOrder;
    }

    public function setCheapestObjectCitySellOrder(string $cheapestObjectCitySellOrder): void
    {
        $this->cheapestObjectCitySellOrder = $cheapestObjectCitySellOrder;
    }

    public function getMostExpensiveObjectCitySellOrder(): string
    {
        return $this->mostExpensiveObjectCitySellOrder;
    }

    public function setMostExpensiveObjectCitySellOrder(string $mostExpensiveObjectCitySellOrder): void
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