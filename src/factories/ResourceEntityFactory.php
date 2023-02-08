<?php

namespace MZierdt\Albion\factories;

use MZierdt\Albion\Entity\ResourceEntity;

class ResourceEntityFactory
{
    public static function getEmptyResourceEntity(): ResourceEntity
    {
        return new ResourceEntity();
    }
}
