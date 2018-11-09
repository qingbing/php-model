<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace Model\validators;

class IpValidator extends PregValidator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"不是有效的IP地址';
    /* @var string IP地址 的正则表达式 */
    public $pattern = '/^(1\d{2}|2[0-4]\d|25[0-4]|[1-9]\d?)(\.(1\d{2}|2[0-4]\d|25[0-4]|[1-9]?\d)){3}$/i';
}