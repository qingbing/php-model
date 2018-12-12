<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace ModelSupports\validators;


class ContactValidator extends PregValidator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"的联系电话无效';
    /* @var string 联系人验证的正则表达式 */
    public $pattern = '/(^0[1-9]\d{1,2}-[1-9]\d{6,7}(-\d{1,4})?$)|(^0?1\d{10}$)/'; // "contact" 的正则验证表达式
}