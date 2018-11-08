<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

class IpValidator extends PregValidator
{
    public $message = '"{attribute}" is not a valid IP.';
    public $pattern = '/^(1\d{2}|2[0-4]\d|25[0-4]|[1-9]\d?)(\.(1\d{2}|2[0-4]\d|25[0-4]|[1-9]?\d)){3}$/i'; // "ip" 的正则验证表达式
}