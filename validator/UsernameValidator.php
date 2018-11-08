<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

class UsernameValidator extends PregValidator
{
    public $message = '"{attribute}" is not a valid username.';
    public $pattern = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_\.]{2,18}$/u';
}