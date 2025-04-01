<?php

namespace App\Serializers;

use League\Fractal\Serializer\DataArraySerializer;

/**
 * CustomSerializer
 * This serializer removes the parent "data" for transformed items.
 * The creators of the Fractal package seem very content on insisting it is used, even for includes. 🤷‍♂️
 *
 * @package App
 */
class CustomSerializer extends DataArraySerializer
{
    public function item($resourceKey, array $data): array
    {
        return $resourceKey ? [$resourceKey => $data] : $data;
    }
}
