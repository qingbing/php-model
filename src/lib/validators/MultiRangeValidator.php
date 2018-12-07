<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-09
 * Version      :   1.0
 */

namespace Model\validators;

use Helper\Exception;
use Abstracts\Validator;

class MultiRangeValidator extends Validator
{
    /* @var string 自定义的错误消息："{attribute}"可以替代成属性的"label" */
    public $message = '"{attribute}"必须为列表赋值';
    /* @var array 范围 */
    public $range = [];
    /* @var bool 是否执行严格验证 */
    public $strict = false;

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
        if (!is_array($this->range)) {
            throw new Exception('属性"range"必须定义为数组', 101400301);
        }

        if (is_array($value)) {
            $noIn = [];
            foreach ($value as $v) {
                if (false === $this->inArray($v)) {
                    $noIn[] = $v;
                }
            }
            $isIn = empty($noIn);
        } else {
            $isIn = $this->inArray($value);
        }

        if (!$isIn) {
            $this->addError($object, $attribute, $this->message);
            return;
        }
    }

    /**
     * 判断值是否在范围之内
     * @param mixed $value
     * @return bool
     */
    protected function inArray($value)
    {
        if ($this->strict) {
            $isIn = in_array($value, $this->range, true);
        } else {
            $isIn = false;
            foreach ($this->range as $r) {
                $isIn = (strcmp($r, $value) === 0);
                if ($isIn) break;
            }
        }
        return $isIn;
    }
}