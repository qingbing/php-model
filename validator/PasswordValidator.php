<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

class PasswordValidator extends PregValidator
{
    public $message = '"{attribute}" is not a valid password.';
    public $pattern = '/^.{6,32}$/';
}