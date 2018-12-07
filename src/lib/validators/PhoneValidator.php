<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-08
 * Version      :   1.0
 */

namespace Model\validators;

class PhoneValidator extends PregValidator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"不是有效的座机号';
    /* @var string "phone" 的正则验证表达式 */
    public $pattern = '/^0[1-9]\d{1,2}-[1-9]\d{6,7}(-\d{1,4})?$/';
}