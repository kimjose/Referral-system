<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduledTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_mfl_sync_schedule()
    {
        $schedule = app()->make(Schedule::class);
        $events = collect($schedule->events())->filter(function ($event) {
            return stripos($event->command, 'integrations:sync --type=mfl') !== false;
        });

        $this->assertCount(1, $events);
        $event = $events->first();
        $this->assertEquals('01:00', $event->expression);
        $this->assertTrue($event->withoutOverlapping);
        $this->assertTrue($event->runInBackground);
    }

    public function test_echis_sync_schedule()
    {
        $schedule = app()->make(Schedule::class);
        $events = collect($schedule->events())->filter(function ($event) {
            return stripos($event->command, 'integrations:sync --type=echis') !== false;
        });

        $this->assertCount(1, $events);
        $event = $events->first();
        $this->assertEquals('*/15 * * * *', $event->expression);
        $this->assertTrue($event->withoutOverlapping);
        $this->assertTrue($event->runInBackground);
    }

    public function test_shr_sync_schedule()
    {
        $schedule = app()->make(Schedule::class);
        $events = collect($schedule->events())->filter(function ($event) {
            return stripos($event->command, 'integrations:sync --type=shr') !== false;
        });

        $this->assertCount(1, $events);
        $event = $events->first();
        $this->assertEquals('*/30 * * * *', $event->expression);
        $this->assertTrue($event->withoutOverlapping);
        $this->assertTrue($event->runInBackground);
    }

    public function test_hie_sync_schedule()
    {
        $schedule = app()->make(Schedule::class);
        $events = collect($schedule->events())->filter(function ($event) {
            return stripos($event->command, 'integrations:sync --type=hie') !== false;
        });

        $this->assertCount(1, $events);
        $event = $events->first();
        $this->assertEquals('0 * * * *', $event->expression);
        $this->assertTrue($event->withoutOverlapping);
        $this->assertTrue($event->runInBackground);
    }
} 