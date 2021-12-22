<?php

namespace Jqqjj\LaravelSimpleJudgeRobot;

use Jqqjj\LaravelSimpleJudgeRobot\Adapter\AdapterInterface;

class Manager
{
    public static $cookie_key = "_simpleJudgeRobot";
    public static $header_real_ip_key = "REMOTE_ADDR";

    private $session;

    public $cookieLifetime = 3600 * 24 * 30;
    public $path = '/';
    public $domain;
    public $secure = false;
    public $httponly = true;

    public $duration = 600;
    public $durationMaxAttempt = 3;

    public function __construct(AdapterInterface $adapter, $session_id=null)
    {
        if($session_id === null){
            $session_id = !empty($_COOKIE) && !empty($_COOKIE[static::$cookie_key]) ? $_COOKIE[static::$cookie_key] : "";
        }
        $this->session = new Session($adapter, $session_id, static::$header_real_ip_key, $this->duration, $this->durationMaxAttempt);
        $this->session->expired_time = date("Y-m-d H:i:s", time() + $this->cookieLifetime);
    }

    public function attemptFailure()
    {
        $this->session->createAttempt(0);
    }

    public function attemptSuccess()
    {
        $this->session->createAttempt(1);
    }

    public function isHuman()
    {
        return $this->session->getRemaining() > 0;
    }

    public function isRobot()
    {
        return $this->session->getRemaining() <= 0;
    }

    public function trashData()
    {
        return $this->session->trashData();
    }

	public function getCookieKey()
	{
		return static::$cookie_key;
	}

	public function getSessionId()
	{
		return $this->session->getId();
	}

    public function getSession()
    {
        return $this->session;
    }

    public function getOutputCookieString()
    {
        $str = static::$cookie_key . "={$this->session->getId()}";
        $str .= ";expires=".gmdate('D, d-M-Y H:i:s T', $this->cookieLifetime + time());
        $str .= ";Max-Age={$this->cookieLifetime}";
        $str .= ";path={$this->path}";
        if(!empty($this->domain)){
            $str .= ";domain={$this->domain}";
        }
        if($this->secure){
            $str .= ";secure";
        }
        if($this->httponly){
            $str .= ";HttpOnly";
        }
        return $str;
    }

    public function outputCookie()
    {
        setcookie(static::$cookie_key, $this->session->getId(), $this->cookieLifetime + time(), $this->path, $this->domain, $this->secure, $this->httponly);
    }
}
