<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-08
 * Version      :   1.0
 */

namespace Model\validators;

use Helper\DateTimeParser;
use Model\Validator;

class TypeValidator extends Validator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"必须是"{type}"类型';
    /* @var string 允许的类型，默认为 "string",支持 'string', 'integer', 'float', 'array', 'date', 'time' , 'datetime' */
    public $type = 'string';
    /* @var string 日期格式字符串 */
    public $dateFormat = 'yyyy-MM-dd';
    /* @var string 时间格式字符串 */
    public $timeFormat = 'hh:mm';
    /* @var string "datetime"格式字符串 */
    public $datetimeFormat = 'yyyy-MM-dd hh:mm';
    /* @var bool 对于值是否采取严格的对照模式 */
    public $strict = false;

    /**
     * 通过当前规则验证属性，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
     * @param \Model\Model $object
     * @param string $attribute
     * @throws \Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->{$attribute};
        if ($this->isEmpty($value)) {
            $this->validateEmpty($object, $attribute);
            return;
        }

        if (!$this->validateValue($value)) {
            $this->addError($object, $attribute, $this->message, ['{type}' => $this->type]);
        }
    }

    /**
     * 检查是否为对应的类型
     * @param mixed $value
     * @return bool
     */
    public function validateValue($value)
    {
        $type = $this->type === 'float' ? 'double' : $this->type;
        if ($type === gettype($value)) {
            return true;
        } else if (
            $this->strict
            || is_array($value)
            || is_object($value)
            || is_resource($value)
            || is_bool($value)
        ) {
            return false;
        }

        switch ($type) {
            case 'integer' :
                return (boolean)preg_match('/^[-+]?[0-9]+$/', trim($value));
            case 'double':
                return (boolean)preg_match('/^[-+]?([0-9]*\.)?[0-9]+([eE][-+]?[0-9]+)?$/', trim($value));
            case 'date':
                return DateTimeParser::parse($value, $this->dateFormat, ['month' => 1, 'day' => 1, 'hour' => 0, 'minute' => 0, 'second' => 0]) !== false;
            case 'time':
                return DateTimeParser::parse($value, $this->timeFormat) !== false;
            case 'datetime':
                return DateTimeParser::parse($value, $this->datetimeFormat, ['month' => 1, 'day' => 1, 'hour' => 0, 'minute' => 0, 'second' => 0]) !== false;
            default :
                return false;
        }
    }
}