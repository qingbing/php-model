<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-11-08
 * Version      :   1.0
 */

namespace ModelSupports\validators;

use Helper\Exception;
use Abstracts\Validator;

class RangeValidator extends Validator
{
    /* @var array 设置值的范围 */
    public $range = [];
    /* @var bool 是否执行严格验证 */
    public $strict = false;
    /* @var bool 是否排除在范围之外，默认在范围之内 */
    public $not = false;

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
            throw new Exception(str_cover('必须指定"range"属性，且为数组列表'), 101400601);
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
            $message = $this->message !== null ? $this->message : '"{attribute}"必须在数组列表之中';
            $this->addError($object, $attribute, $message);
            return;
        }
        if ($this->not && $isIn) {
            $message = $this->message !== null ? $this->message : '"{attribute}"必须在数组列表之外';
            $this->addError($object, $attribute, $message);
        }
    }
}