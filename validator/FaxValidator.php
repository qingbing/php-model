<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

class FaxValidator extends PregValidator
{
    public $message = '"{attribute}" is not a valid fax.';
    public $pattern = '/^0[1-9]\d{1,2}-[1-9]\d{6,7}(-\d{1,4})?$/'; // "fax" 的正则验证表达式
}