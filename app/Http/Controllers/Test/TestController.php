<?php

namespace App\Http\Controllers\Test;

use App\Jobs\ProcessPodcast;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class TestController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 缓存测试
     * @param Request $request
     */
    public function testCache(Request $request)
    {
        dump('缓存测试');
        Cache::put('testCache', 'yueguang', now()->addMinutes(10));
        $testCache = Cache::get('testCache');
        dump('缓存测试:'.$testCache);
        $id =1;
        return Cache::remember('user1', 3600, function () use ($id) {
            dump('没有，进来了');
            return User::query()->where('id',$id)->first();
        });
    }

    public function testQueue()
    {
        dump('缓存队列');
        //ProcessPodcast::dispatch(['id'=>1,'msg'=>'测试']);
        ProcessPodcast::dispatch(['id'=>2,'msg'=>'测试'])
            ->delay(now()->addMinutes(1));
    }

}
