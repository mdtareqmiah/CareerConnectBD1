<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

abstract class ApiRequest extends FormRequest
{
    public function expectsJson(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        if ($this->hasHeader('Content-Type') && str_contains($this->header('Content-Type'), 'application/json')) {
            return;
        }

        $this->merge($this->json()->all());
    }
}
