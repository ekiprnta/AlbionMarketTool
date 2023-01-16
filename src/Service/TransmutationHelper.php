<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\Entity\TransmutationEntity;

class TransmutationHelper extends Market
{
    public function reformatResources(array $resources): array
    {
        $formatResource = [];
        foreach ($resources as $resource) {
            $formatResource[$resource->getTier()] = $resource;
        }
        return $formatResource;
    }

    public function transmute(array $transmutationWays, ResourceEntity $resource, array $cost): array
    {
        $transmutation = [];
        foreach ($transmutationWays as $path => $transmutationWay) {
            $currentTier = $resource->getTier();
            $transmutation[$path] = 0;
            foreach ($transmutationWay as $tier) {
                if ($this->sameTier($currentTier, $tier)) {
                    $transmutation[$path] += $cost[$tier]['enchantment'];
                } else {
                    $transmutation[$path] += $cost[$tier]['tier'];
                    $currentTier++;
                }
            }
        }
        return $transmutation;
    }

    private function sameTier(string $currentTier, string $tier): bool
    {
        return $tier[0] === $currentTier[0];
    }

    /** @var ResourceEntity[] $resources */
    public function getEntityList(array $transmutePricing, array $resources): array
    {
        $entityList = [];
        foreach ($transmutePricing as $path => $transmutePrice) {
            [$startTier, $endTier] = $this->getStartAndEndTier($path);

            $entityList[] = new TransmutationEntity($resources[$startTier], $resources[$endTier], $transmutePrice);
        }
        return $entityList;
    }

    private function getStartAndEndTier(string $path)
    {
        return explode('to', $path);
    }
}