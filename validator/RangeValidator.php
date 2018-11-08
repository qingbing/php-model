<?php
/**
 * @date        2017-12-15
 * @author      qingbing<780042175@qq.com>
 * @version     1.0
 */

namespace pf\validator;

use pf\exception\Exception;
use pf\helper\Unit;

class RangeValidator extends Validator
{
    public $range = []; // 值的范围
    public $strict = false; // 是否执行严格验证
    public $not = false; // 是否排除在范围之外，默认在范围之内

    /**
     * 安全规则，不需要做任何验证规则
     * @param \pf\core\Model $object
     * @param $attribute
     * @throws Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;
        if ($this->isEmpty($value)) {
            $this->validateEmpty($object, $attribute);
            return;
        }
        if (!is_array($this->range)) {
            throw new Exception(Unit::replace('The "range" property must be specified with a list of values.'));
        }
        $isIn = false;
        if ($this->strict) {
            $isIn = in_array($value, $this->range, true);
        } else {
            foreach ($this->range as $r) {
                $isIn = (strcmp($r, $value) === 0);
                if ($isIn) break;
            }
        }
        if (!$this->not && !$isIn) {
            $message = $this->message !== null ? $this->message : '"{attribute}" is not in the list.';
            $this->addError($object, $attribute, $message);
            return;
        }
        if ($this->not && $isIn) {
            $message = $this->message !== null ? $this->message : '"{attribute}" must be out of the list.';
            $this->addError($object, $attribute, $message);
        }
    }
}