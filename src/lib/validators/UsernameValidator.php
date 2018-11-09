<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-09
 * Version      :   1.0
 */

namespace Model\validators;

class UsernameValidator extends PregValidator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"是无效用户名';
    /* @var string 正则表达式 */
    public $pattern = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_\.]{2,18}$/u';
}