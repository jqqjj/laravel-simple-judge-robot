<?php

namespace Jqqjj\LaravelSimpleJudgeRobot;

use Jqqjj\LaravelSimpleJudgeRobot\Attributes\SessionAttributes;
use Jqqjj\LaravelSimpleJudgeRobot\Adapter\AdapterInterface;
use Jqqjj\LaravelSimpleJudgeRobot\Exception\RuntimeException;

class Session
{
    private $adapter;
    protected $attributes;
    protected $original_attributes;
    protected $duration = 600;
    protected $duration_attempt_num = 3;
    protected $header_real_ip_key = 'REMOTE_ADDR';

    public function __construct(AdapterInterface $adapter, $session_id, $header_real_ip_key, $duration, $duration_attempt_num)
    {
        $this->adapter = $adapter;
        $this->header_real_ip_key = $header_real_ip_key;
        $this->duration = $duration;
        $this->duration_attempt_num = $duration_attempt_num;
        $attributes = $adapter->getSession($session_id);
        $remaining = $this->_getIPRemaining($_SERVER[$this->header_real_ip_key]);
        if(empty($attributes) || strtotime($attributes['expired_time'])<time()){
            $session_id = md5(uniqid("", true).mt_rand(100, 999));
            $this->adapter->createSession($session_id, $remaining, time());
            $attributes = $this->adapter->getSession($session_id);
            if(empty($attributes)){
                throw new RuntimeException("Add Session Error.");
            }
            $attributes['remaining'] = max(array($remaining,$attributes['remaining']));
            $this->_loadAttributes($attributes);
            $this->_freshenOriginalAttributes();
        }else{
            $attributes['remaining'] = max(array($remaining,$attributes['remaining']));
            $this->_loadAttributes($attributes);
            $this->_freshenOriginalAttributes();
        }
    }

    public function getId()
    {
        return $this->attributes->session_id;
    }

    public function getRemaining()
    {
        return $this->attributes->remaining;
    }

    public function getAttempts()
    {
        return $this->adapter->getSessionAttempts($this->getId(), $this->duration_attempt_num);
    }

    public function createAttempt($status)
    {
        if($status){
            $this->resetRemaining();
        }else{
            $this->adapter->createAttempt($this->getId(), 0, time(), $_SERVER[$this->header_real_ip_key]);
            $this->attributes->remaining = max(array($this->attributes->remaining - 1,0));
        }
    }

    public function resetRemaining()
    {
        $this->attributes->remaining = $this->duration_attempt_num;
    }

    public function trashData()
    {
        return $this->adapter->trashData();
    }

    public function save()
    {
        $data = $this->attributes->toArray();
        $original_data = $this->original_attributes->toArray();
        $save_data = array_diff_assoc($data, $original_data);
        if(empty($save_data)){
            return true;
        }
        if($this->adapter->updateSession($this->getId(), $save_data)){
            $this->_freshenOriginalAttributes();
            return true;
        }else{
            return false;
        }
    }

    private function _loadAttributes(array $attributes)
    {
        $this->attributes = new SessionAttributes();
        foreach ($attributes as $key=>$value){
            if(isset($this->attributes->$key)){
                $this->attributes->$key = $value;
            }
        }
    }

    private function _freshenOriginalAttributes()
    {
        $this->original_attributes = clone $this->attributes;
    }

    private function _getIPRemaining($ip)
    {
        $attempts = $this->adapter->getIPAttempts($ip, $this->duration_attempt_num);
        if(empty($attempts)){
            return $this->duration_attempt_num;
        }

        $use = 0;
        foreach ($attempts as $value){
            if($value['status'] || time() - strtotime($value['add_time']) > $this->duration){
                break;
            }
            $use++;
        }
        return max(array($this->duration_attempt_num - $use , 0));
    }

    public function __set($name, $value)
    {
        $this->attributes->$name = $value;
    }

    public function __get($name)
    {
        return $this->attributes->$name;
    }

    public function __destruct()
    {
        $this->save();
    }
}
