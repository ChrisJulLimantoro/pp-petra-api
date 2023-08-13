<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ControllerUtils
{
    public static function validateRequest(
        Model $model,
        $request,
        $isPatch = false,
    ) {
        if ($isPatch) {
            if (empty($request)) {
                throw new BadRequestException('Empty request Body!!');
            }

            $validationRules = [];
            $modelValidationRules = $model->validationRules();
            foreach ($request as $field => $value) {
                if (array_key_exists($field, $modelValidationRules)) {
                    $validationRules[$field] = $modelValidationRules[$field];
                }
            }
        } else {
            $validationRules = $model->validationRules();
        }

        $validator = Validator::make(
            $request,
            $validationRules,
            $model->validationMessages()
        );
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
