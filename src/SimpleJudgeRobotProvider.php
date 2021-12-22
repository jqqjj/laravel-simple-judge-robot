<?php

namespace Jqqjj\LaravelSimpleJudgeRobot;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
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
            $adapter = new DBTableGateway(DB::connection()->getPdo());
            $m = new Manager($adapter, Cookie::get(Manager::$cookie_key, ''));
            Cookie::queue(Manager::$cookie_key, $m->getSessionId(), $m->cookieLifetime / 60);
            return $m;
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
