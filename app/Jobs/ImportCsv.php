<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportCsv implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $page;
    public $num;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($page,$num)
    {
        $this->page = $page;
        $this->num = $num;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('处理批次开始:'.$this->batch()->id, [ $this->page ,$this->num]);
        if ($this->batch()->cancelled()) {
            // 确定批次是否已取消...
            Log::info('确定批次是否已取消');
            return;
        }
        sleep(5);
        Log::info('处理完成批次:'.$this->batch()->id, [ $this->page ,$this->num]);

    }
}
