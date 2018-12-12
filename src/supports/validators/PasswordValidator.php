<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-08
 * Version      :   1.0
 */

namespace ModelSupports\validators;


class PasswordValidator extends PregValidator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"不是有效的密码';
    /* @var string 正则表达式 */
    public $pattern = '/^.{6,32}$/';
}