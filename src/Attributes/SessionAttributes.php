<?php

namespace Jqqjj\LaravelSimpleJudgeRobot\Attributes;

class SessionAttributes extends AbstractAttributes implements AttributesInterface
{
    protected $session_id;
    protected $remaining;
    protected $expired_time;
}
