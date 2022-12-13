<?php

namespace App\Jobs\Middleware;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redis;

class RateLimited
{
    /**
     * 处理队列任务
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
       /* Redis::throttle('key')
            ->block(0)->allow(1)->every(5)
            ->then(function () use ($job, $next) {
                // 获得了锁
                dump('获得锁');
                $next($job);
            }, function () use ($job) {
                // 没有获取到锁
                dump('没有获取到锁');
                $job->release(5);
            });*/
    }
}
