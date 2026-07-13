<?php

namespace App\Services\AI;

use Illuminate\Contracts\Container\BindingResolutionException;
use RuntimeException;

class AIManager
{
    public function __construct(private AIServiceInterface $service)
    {
    }

    public function getService(): AIServiceInterface
    {
        return $this->service;
    }
}
