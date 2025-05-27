<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MFLService;
use App\Services\ECHISService;
use App\Services\SHRService;
use App\Services\HIEService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SyncIntegrationsCommand extends Command
{
    protected $signature = 'integrations:sync {--type= : The type of integration to sync (mfl, echis, shr, hie)}';
    protected $description = 'Sync data with external integrations';

    protected $validTypes = ['mfl', 'echis', 'shr', 'hie'];

    public function handle()
    {
        $type = $this->option('type');

        if (!$type || !in_array($type, $this->validTypes)) {
            $this->error("Invalid integration type: {$type}");
            return 1;
        }

        $cacheKey = "integration_sync_{$type}_running";
        if (Cache::get($cacheKey)) {
            $this->info("Another {$type} sync is already running");
            return 0;
        }

        try {
            Cache::put($cacheKey, true, 60);
            $this->info("Starting {$type} synchronization...");

            $result = $this->syncIntegration($type);

            if ($result['success']) {
                $this->info($result['message']);
                $this->logSync($type, 'success', $result['message']);
            } else {
                $this->error($result['message']);
                $this->logSync($type, 'error', $result['message']);
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->logSync($type, 'error', $e->getMessage());
            return 1;
        } finally {
            Cache::forget($cacheKey);
        }
    }

    protected function syncIntegration($type)
    {
        $service = $this->getService($type);
        return $service->sync();
    }

    protected function getService($type)
    {
        return match ($type) {
            'mfl' => app(MFLService::class),
            'echis' => app(ECHISService::class),
            'shr' => app(SHRService::class),
            'hie' => app(HIEService::class),
            default => throw new \InvalidArgumentException("Invalid integration type: {$type}")
        };
    }

    protected function logSync($type, $status, $message)
    {
        Log::info("Integration sync", [
            'type' => $type,
            'status' => $status,
            'message' => $message
        ]);

        \App\Models\SyncLog::create([
            'type' => $type,
            'status' => $status,
            'message' => $message
        ]);
    }
} 