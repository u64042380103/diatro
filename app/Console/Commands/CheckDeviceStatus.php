<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckDeviceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update the device status based on the set time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $current_time = date('H:i');
        // Get all devices and their set times
        $devices = ModelControlSystem::with(['setTimes' => function ($query) {
            $query->orderBy('time', 'asc');
        }])->get();

        foreach ($devices as $device) {
            foreach ($device->setTimes as $setTime) {
                if ($current_time == $setTime->time && $setTime->on_off == 1) {
                    $status = 'on';
                } else {
                    $status = 'off';
                }

                // Update the device status
                DB::table('controlsystems')->where('device_id', $device->device_id)->update([
                    'status' => $status,
                ]);
            }
        }
        return 0;
    }
}
