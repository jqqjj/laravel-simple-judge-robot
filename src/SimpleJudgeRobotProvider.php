<?php

namespace Jqqjj\LaravelSimpleJudgeRobot;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Jqqjj\LaravelSimpleJudgeRobot\Adapter\DBTableGateway;

class SimpleJudgeRobotProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('judgeRobot',function (){
            $pdo = DB::connection()->getPdo();
            $adapter = new DBTableGateway($pdo);
            return new Manager($adapter);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }
}
