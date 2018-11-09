<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\exception\Exception;
use pf\helper\Unit;

class MultiRangeValidator extends Validator
{
    public $message = '"{attribute}" is not in the list.';
    public $range = []; // 值的范围
    public $strict = false; // 是否执行严格验证

    /**
     * 安全规则，不需要做任何验证规则
     * @param \pf\core\Model $object
     * @param $attribute
     * @throws Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->{$attribute};
        if ($this->isEmpty($value)) {
            $this->validateEmpty($object, $attribute);
            return;
        }
        if (!is_array($this->range)) {
            throw new Exception(Unit::replace('The "range" property must be specified with a list of values.'));
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