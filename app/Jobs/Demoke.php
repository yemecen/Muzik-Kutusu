<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class Demoke implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name=$name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug($this->name);
    }
}
