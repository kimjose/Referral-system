<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IntegrationService;

class SyncIntegrations extends Command
{
    protected $signature = 'integrations:sync {--type=all : Type of sync to perform (mfl, echis, shr, hie, or all)}';
    protected $description = 'Sync data with MFL, eCHIS, SHR, and HIE systems';

    protected $integrationService;

    public function __construct(IntegrationService $integrationService)
    {
        parent::__construct();
        $this->integrationService = $integrationService;
    }

    public function handle()
    {
        $type = $this->option('type');

        switch ($type) {
            case 'mfl':
                $this->syncMFL();
                break;
            case 'echis':
                $this->syncECHIS();
                break;
            case 'shr':
                $this->syncSHR();
                break;
            case 'hie':
                $this->syncHIE();
                break;
            case 'all':
                $this->syncMFL();
                $this->syncECHIS();
                $this->syncSHR();
                $this->syncHIE();
                break;
            default:
                $this->error('Invalid sync type. Use: mfl, echis, shr, hie, or all');
                return 1;
        }

        return 0;
    }

    protected function syncMFL()
    {
        $this->info('Starting MFL facilities sync...');
        $result = $this->integrationService->syncMFLFacilities();
        
        if ($result) {
            $this->info('MFL facilities sync completed successfully');
        } else {
            $this->error('MFL facilities sync failed');
        }
    }

    protected function syncECHIS()
    {
        $this->info('Starting eCHIS referrals sync...');
        $result = $this->integrationService->syncECHISReferrals();
        
        if ($result) {
            $this->info('eCHIS referrals sync completed successfully');
        } else {
            $this->error('eCHIS referrals sync failed');
        }
    }

    protected function syncSHR()
    {
        $this->info('Starting SHR health records sync...');
        $result = $this->integrationService->syncSHRRecords();
        
        if ($result) {
            $this->info('SHR health records sync completed successfully');
        } else {
            $this->error('SHR health records sync failed');
        }
    }

    protected function syncHIE()
    {
        $this->info('Starting HIE records sync...');
        $result = $this->integrationService->syncHIERecords();
        
        if ($result) {
            $this->info('HIE records sync completed successfully');
        } else {
            $this->error('HIE records sync failed');
        }
    }
} 