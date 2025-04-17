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
    public function collection(?string $resourceKey = 'data', array $data = []): array
    {
        // Pass an empty string to avoid nesting the data.
        return !empty($resourceKey) ? [$resourceKey => $data] : $data;
    }

    public function item(?string $resourceKey, array $data): array
    {
        return $resourceKey ? [$resourceKey => $data] : $data;
    }
}
