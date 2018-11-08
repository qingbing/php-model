<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\helper\DateTimeParser;

class DateValidator extends Validator
{
    public $message = '"{attribute}" does not match the date format.';
    public $format = 'yyyy-MM-dd'; // 时间的格式化形式
    public $timestampAttribute; // 定义的字段来接受日期的时间戳

    /**
     * "date"相关规则验证
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
        $valid = false;
        if (!is_array($value)) {
            $formats = is_string($this->format) ? [$this->format] : $this->format;
            foreach ($formats as $format) {
                $timestamp = DateTimeParser::parse($value, $format, ['month' => 1, 'day' => 1, 'hour' => 0, 'minute' => 0, 'second' => 0]);
                if (false !== $timestamp) {
                    $valid = true;
                    if (null !== $this->timestampAttribute)
                        $object->{$this->timestampAttribute} = $timestamp;
                    break;
                }
            }
        }

        if (!$valid) {
            $this->addError($object, $attribute, $this->message);
        }
    }
}