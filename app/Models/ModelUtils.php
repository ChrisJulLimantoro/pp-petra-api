<?php

namespace App\Models;

class ModelUtils
{
    public static function filterNullValues(array $data): array
    {
        return array_filter($data, function ($value) {
            return $value !== null;
        });
    }
}
