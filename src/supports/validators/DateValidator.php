<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-07
 * Version      :   1.0
 */

namespace ModelSupports\validators;

use Helper\DateTimeParser;
use Abstracts\Validator;

class DateValidator extends Validator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"不是有效的日期格式';
    /* @var string 日期的格式化形式 */
    public $format = 'yyyy-MM-dd';
    /* @var string 定义的字段来接受日期的时间戳 */
    public $timestampAttribute;

    /**
     * 通过当前规则验证属性，如果有验证不通过的情况，将通过 model 的 addError 方法添加错误信息
     * @param \Abstracts\Model $object
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
        $valid = false;
        if (!is_array($value)) {
            $formats = is_string($this->format) ? [$this->format] : $this->format;
            foreach ($formats as $format) {
                $timestamp = DateTimeParser::parse($value, $format, ['month' => 1, 'day' => 1, 'hour' => 0, 'minute' => 0, 'second' => 0]);
                if (false !== $timestamp) {
                    $valid = true;
                    if (null !== $this->timestampAttribute) {
                        $object->{$this->timestampAttribute} = $timestamp;
                    }
                    break;
                }
            }
        }

        if (!$valid) {
            $this->addError($object, $attribute, $this->message);
        }
    }
}