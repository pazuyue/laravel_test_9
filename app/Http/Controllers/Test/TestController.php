<?php

namespace App\Http\Controllers\Test;

use App\Jobs\ImportCsv;
use App\Jobs\OptimizePodcast;
use App\Jobs\ProcessPodcast;
use App\Jobs\ReleasePodcast;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Bus\Batch;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Throwable;

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
        $user = Cache::remember('user1', 3600, function () use ($id) {
            dump('没有，进来了');
            return User::query()->where('id',$id)->first();
        });

        return response()->json([
            'state' => 'success',
            'msg' => $user,
        ]);
    }

    /**
     * 测试队列
     */
    public function testQueue()
    {
        dump('缓存队列');
        //ProcessPodcast::dispatch(['id'=>1,'msg'=>'测试']);
       /* ProcessPodcast::dispatch(['id'=>2,'msg'=>'测试'])
            ->delay(now()->addMinutes(1));*/
        Bus::chain([
            new ProcessPodcast(['id'=>1,'msg'=>'ProcessPodcast']),
            new ReleasePodcast(['id'=>1,'msg'=>'ProcessPodcast']),
            new OptimizePodcast(['id'=>2,'msg'=>'OptimizePodcast']),
            ])->catch(function (Throwable $e) {
                dump($e->getMessage());
        })->dispatch();
    }

    public function testQueueBatch()
    {
        dump('测试任务批处理');
        try {
            $batch = Bus::batch([
                new ImportCsv(1, 100),
                new ImportCsv(101, 200),
                new ImportCsv(201, 300),
                new ImportCsv(301, 400),
                new ImportCsv(401, 500),
            ])->then(function (Batch $batch) {
                // 所有任务均已成功完成...
                Log::info("所有任务均已成功完成 {$batch->id}");
            })->catch(function (Batch $batch, Throwable $e) {
                // 检测到第一批任务失败...
                Log::info("检测到任务ID {$batch->id}任务失败:".$e->getMessage());
            })->finally(function (Batch $batch) {
                // 批处理已完成执行...
                Log::info('批处理已完成执行'.$batch->id);
            })->name('Import CSV')->onQueue('imports')->dispatch();
        }catch (Throwable $exception){
            dump($exception->getMessage());
        }
    }

    /**
     * 测试日志驱动
     */
    public function testLog()
    {
        dump('测试日志驱动');
        Log::info('测试日志驱动');
    }

    /**
     * 测试多数据库源
     */
    public function testDataBase()
    {
        dump('测试多数据库源');
     /*   $user = new User();
        $user->setConnection('oms');
        $user->name = '月光';
        $user->email = 'xxx';
        $user->password = 'xxx';
        $user->save();*/

        $user = User::on('oms')->first();
        dd($user->toArray());
    }

    public function testAuth()
    {
        $userOl = User::query()->first()->toArray();
        $status = Password::reset(
            $userOl,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        dump($status);
        $credentials = [
            'email'=>'284934551@qq.com',
            'password'=>'xxx',
        ];

        dump($credentials,$userOl);
        if (Auth::attempt($credentials)) {
            // 获取当前的认证用户信息 ...
            $user = Auth::user();
            dd($user);
        }

    }

}
