<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace Model\validators;


class DateValidator extends PregValidator
{
    public $message = '"{attribute}" does not match the datetime format.';
    public $format = 'yyyy-MM-dd hh:mm:ss'; // 时间的格式化形式
}