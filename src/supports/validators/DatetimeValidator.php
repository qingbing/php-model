<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace ModelSupports\validators;

class DatetimeValidator extends DateValidator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"不是有效的时间格式';
    /* @var string 时间的格式化形式 */
    public $format = 'yyyy-MM-dd hh:mm:ss';
}