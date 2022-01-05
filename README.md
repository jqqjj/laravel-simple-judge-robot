# laravel-simple-judge-robot

A simple way to determine whether the client is a machine

## installation

```php
composer require jqqjj/laravel-simple-judge-robot
```

## methods

- void JudgeRobot::attemptFailure()
- void JudgeRobot::attemptSuccess()
- bool JudgeRobot::isHuman()
- bool JudgeRobot::isRobot()
- void JudgeRobot::trashData()
- string JudgeRobot::getCookieKey()
- string JudgeRobot::getSessionId()
- \Jqqjj\LaravelSimpleJudgeRobot\Session JudgeRobot::getSession()
- string JudgeRobot::getOutputCookieString()
- void JudgeRobot::outputCookie()

## usage

- Call JudgeRobot::attemptFailure() method when clients incorrectly access sensitive operations.
- Call JudgeRobot::attemptSuccess() method when clients correctly accesses sensitive operations.
- Call JudgeRobot::isHuman() method to determine whether the client is human.
- Call JudgeRobot::isRobot() method to determine whether the client is a robot.
- Call JudgeRobot::trashData() method to clear history data.

## LICENCE
MIT
