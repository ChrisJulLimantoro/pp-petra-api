<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ServiceUtils
{
    public static function validateFilterParams($model, $filterParams)
    {
        if (! empty($filterParams)) {
            foreach ($filterParams as $key => $value) {
                if (substr($key, -3) == '_id') {
                    throw new BadRequestException("ID can't be filtered!");
                }

                if (! in_array($key, $model->getFillable())) {
                    throw new BadRequestException(sprintf(
                        'Parameter %s is not valid',
                        $key
                    ));
                }
            }
        }
    }
}
