<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    /**
     * Check the health of the application.
     */
    public function __invoke(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'redis' => $this->checkRedis(),
        ];

        $allOk = true;
        foreach ($checks as $check) {
            if ($check['status'] === false) {
                $allOk = false;
                break;
            }
        }

        return response()->json([
            'status' => $allOk ? 'ok' : 'error',
            'timestamp' => now()->toIso8601String(),
            'services' => $checks,
        ], $allOk ? 200 : 503);
    }

    /**
     * Check database connection.
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => true, 'message' => 'Connected'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Connection failed: ' . $e->getMessage()];
        }
    }

    /**
     * Check cache functionality.
     */
    private function checkCache(): array
    {
        try {
            Cache::put('health_check', true, 10);
            $val = Cache::get('health_check');
            Cache::forget('health_check');
            return ['status' => $val === true, 'message' => $val === true ? 'Working' : 'Cache retrieval failed'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Cache error: ' . $e->getMessage()];
        }
    }

    /**
     * Check Redis connection.
     */
    private function checkRedis(): array
    {
        try {
            $redis = Redis::connection();
            $redis->ping();
            return ['status' => true, 'message' => 'Connected'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Redis connection failed: ' . $e->getMessage()];
        }
    }
}
