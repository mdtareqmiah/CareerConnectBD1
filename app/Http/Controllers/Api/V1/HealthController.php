<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;

class HealthController extends BaseApiController
{
    public function index(): JsonResponse
    {
        return $this->success([
            'version' => 'v1',
            'status' => 'healthy',
        ], 'CareerConnectBD API is running.');
    }
}
