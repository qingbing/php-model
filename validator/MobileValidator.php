<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

class MobileValidator extends PregValidator
{
    public $message = '"{attribute}" is not a valid mobile number.';
    public $pattern = '/^0?1\d{10}$/'; // "mobile" 的正则验证表达式
}