<?php

/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/28
 * Time: 14:57
 */
abstract class ResponseHandle
{
    abstract function handle($keyword,$fromUserName,$toUserName);
}