<?php

namespace Jqqjj\LaravelSimpleJudgeRobot\Facades;

use Illuminate\Support\Facades\Facade;
use Jqqjj\LaravelSimpleJudgeRobot\Session;

/**
 * Class JudgeRobot
 * @package Jqqjj\LaravelSimpleJudgeRobot\Facades
 *
 * @method static void attemptFailure()
 * @method static void attemptSuccess()
 * @method static bool isHuman()
 * @method static bool isRobot()
 * @method static void trashData()
 * @method static string getCookieKey()
 * @method static string getSessionId()
 * @method static Session getSession()
 * @method static string getOutputCookieString()
 * @method static void outputCookie()
 *
 * @see \Jqqjj\LaravelSimpleJudgeRobot\Manager
 */
class JudgeRobot extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "judgeRobot";
    }
}
