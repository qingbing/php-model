<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\helper\DateTimeParser;

class TypeValidator extends Validator
{
    public $message = '"{attribute}" must be {type}.';
    /**
     * 允许的类型，默认为 "string"
     * 可以支持 'string', 'integer', 'float', 'array', 'date', 'time' , 'datetime'
     * @var string
     */
    public $type = 'string';
    /**
     * 日期格式字符串
     * @var string
     */
    public $dateFormat = 'yyyy-MM-dd';
    /**
     * 时间格式字符串
     * @var string
     */
    public $timeFormat = 'hh:mm';
    /**
     * "datetime"格式字符串
     * @var string
     */
    public $datetimeFormat = 'yyyy-MM-dd hh:mm';
    /**
     * 对于值是否采取严格的对照模式
     * @var bool
     */
    public $strict = false;

    /**
     * 类型的验证格式
     * @param \pf\core\Model $object
     * @param $attribute
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;
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