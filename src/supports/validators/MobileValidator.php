<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace ModelSupports\validators;

class MobileValidator extends PregValidator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"不是有效的手机号';
    /* @var string "mobile" 的正则验证表达式 */
    public $pattern = '/^0?1\d{10}$/';
}